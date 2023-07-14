<?php defined( 'App' ) or die( 'BoidCMS' );
/**
 *
 * Simple, fast, super extensible
 *
 * Fork of WonderCMS
 * @link https://www.wondercms.com
 *
 * @package BoidCMS
 * @author Shuaib Yusuf Shuaib
 * @link https://boidcms.github.io
 * @version 2.0.1
 * @licence MIT
 */
#[AllowDynamicProperties]
class App {
  /**
   * Current working directory
   * @var string $root
   */
  public $root;
  
  /**
   * Current page
   * @var string $page
   */
  public $page;
  
  /**
   * Array list of uploaded files
   * @var array $medias
   */
  public $medias;
  
  /**
   * Array list of all themes
   * @var array $themes
   */
  public $themes;
  
  /**
   * Array list of all plugins
   * @var array $plugins
   */
  public $plugins;
  
  /**
   * Current installed version
   * @var string $version
   */
  public $version;
  
  /**
   * Admin login status
   * @var bool $logged_in
   */
  public $logged_in;
  
  /**
   * Array container of actions
   * @var array $actions
   */
  protected $actions;
  
  /**
   * Decoded version of database
   * @var array $database
   */
  protected $database;
  
  /**
   * Constructor
   * @param string $root
   */
  public function __construct( string $root ) {
    $this->root = $root;
    if ( ! is_file( $this->root( 'data/database.json' ) ) ) {
      $config = require $this->root( 'config.php' );
      ( is_dir( $this->root( 'data' ) ) ?: mkdir( $this->root( 'data' ) ) );
      ( is_dir( $this->root( 'media' ) ) ?: mkdir( $this->root( 'media' ) ) );
      ( is_dir( $this->root( 'plugins' ) ) ?: mkdir( $this->root( 'plugins' ) ) );
      $json = json_encode( $config, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE );
      file_put_contents( $this->root( 'data/database.json' ), $json, LOCK_EX );
    }
    $this->actions = array();
    $this->version = '2.0.1';
    $this->logged_in = ( isset( $_SESSION[ 'logged_in' ], $_SESSION[ 'root' ] ) && $this->root === $_SESSION[ 'root' ] );
    $this->database = json_decode( file_get_contents( $this->root( 'data/database.json' ) ), true );
    $this->plugins = array_map( 'basename', glob( $this->root( 'plugins/*' ), GLOB_ONLYDIR ) );
    $this->themes = array_map( 'basename', glob( $this->root( 'themes/*' ), GLOB_ONLYDIR ) );
    $this->medias = array_map( 'basename', array_filter( glob( $this->root( 'media/*' ) ), 'is_file' ) );
    $this->page = $this->esc( $_GET[ 'p' ] ?? '' );
  }
  
  /**
   * Filter value
   * @param mixed $value
   * @param string $action
   * @param mixed ...$args
   * @return mixed
   */
  public function _( mixed $value, string $action = 'default', mixed ...$args ): mixed {
    return $this->get_filter( $value, $action, ...$args );
  }
  
  /**
   * Array custom list filter
   * @param string $action
   * @param array $custom
   * @return array
   */
  public function _l( string $action, array $custom = array() ): array {
    $option = $this->get_action( $action );
    $option =            ( $option ?? '' );
    $option =      explode( ',', $option );
    $option = array_map( 'trim', $option );
    $option =      array_filter( $option );
    $option =      array_unique( $option );
    return array_merge( $option, $custom );
  }
  
  /**
   * Log a debug message
   * @param string $message
   * @param string $type
   * @return bool
   */
  public function log( string $message, string $type = 'debug' ): bool {
    $type = addslashes( $type );
    $message = addslashes( $message );
    $page = addslashes( $this->page );
    $this->get_action( 'log', $message, $type );
    $msg = sprintf( '[%s] [%d "%s"] : [%s] - %s', date( 'c' ), http_response_code(), $page, strtoupper( $type ), $message . PHP_EOL );
    return error_log( $msg, 3, $this->root( 'data/debug.log' ) );
  }
  
  /**
   * Set pair of key value to database
   * @param mixed $value
   * @param string $index
   * @return bool
   */
  public function set( mixed $value, string $index ): bool {
    $this->database[ 'site' ][ $index ] = $value;
    return $this->save();
  }
  
  /**
   * Delete a key from database
   * @param string $index
   * @return bool
   */
  public function unset( string $index ): bool {
    unset( $this->database[ 'site' ][ $index ] );
    return $this->save();
  }
  
  /**
   * Get value of key from database
   * @param string $index
   * @return mixed
   */
  public function get( string $index ): mixed {
    return ( $this->data( 'site' )[ $index ] ?? null );
  }
  
  /**
   * Get full site url
   * @param string $location
   * @return string
   */
  public function url( string $location = '' ): string {
    return $this->get_filter( $this->get( 'url' ) . $location, 'url', $location );
  }
  
  /**
   * Absolute or relative admin url
   * @param string $location
   * @param bool $abs
   * @return string
   */
  public function admin_url( string $location = '', bool $abs = false ): string {
    $location = ( $this->get( 'admin' ) . $location );
    return ( $abs ? $this->url( $location ) : $location );
  }
  
  /**
   * Get filename from root
   * @param string $location
   * @return string
   */
  public function root( string $location ): string {
    return ( $this->root . '/' .$location );
  }
  
  /**
   * Get filename from current theme directory
   * @param string $location
   * @param string $system
   * @return string
   */
  public function theme( string $location, bool $system = true ): string {
    $location = ( 'themes/' . $this->get( 'theme' ) . '/' . $location );
    return ( $system ? $this->root( $location ) : $this->url( $location ) );
  }
  
  /**
   * Save database changes
   * @param ?array $data
   * @return bool
   */
  public function save( ?array $data = null ): bool {
    $data ??= $this->data();
    $json = json_encode( $data, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE );
    $whole = isset( $data[ 'site' ], $data[ 'pages' ], $data[ 'installed' ] );
    if ( empty( $data ) || ! $whole || json_last_error() !== JSON_ERROR_NONE ) {
      $this->log( 'An error occurred while trying to save database.', 'error' );
      return false;
    }
    $this->get_action( 'save' );
    $database = $this->root( 'data/database.json' );
    return ( bool ) file_put_contents( $database, $json, LOCK_EX );
  }
  
  /**
   * Readonly version of database
   * @param ?string $index
   * @return array
   */
  public function data( ?string $index = null ): array {
    $data = $this->database;
    if ( null !== $index ) {
      return ( $data[ $index ] ?? array() );
    }
    return $data;
  }
  
  /**
   * CSRF token
   * @return string
   */
  public function token(): string {
    return ( $_SESSION[ 'token' ] ??= bin2hex( random_bytes(32) ) );
  }
  
  /**
   * Set a callback function to action
   * @param string | array $action
   * @param callable $callback
   * @param int $priority
   * @return void
   */
  public function set_action( string | array $action, callable $callback, int $priority = 10 ): void {
    if ( is_array( $action ) ) {
      foreach ( $action as $act ) {
        $this->actions[ $act ][ $priority ][] = $callback;
        ksort( $this->actions[ $act ] );
      }
      return;
    }
    $this->actions[ $action ][ $priority ][] = $callback;
    ksort( $this->actions[ $action ] );
  }
  
  /**
   * Unset a given action
   * @param string $action
   * @return void
   */
  public function unset_action( string $action ): void {
    unset( $this->actions[ $action ] );
  }
  
  /**
   * Trigger an action
   * @param string $action
   * @param mixed ...$args
   * @return mixed
   */
  public function get_action( string $action, mixed ...$args ): mixed {
    $result = null;
    $this->load_actions();
    if ( isset( $this->actions[ $action ] ) ) {
      foreach ( $this->actions[ $action ] as $priorities ) {
        foreach ( $priorities as $callback ) {
          $result .= $callback( ...$args );
        }
      }
    }
    return $result;
  }
  
  /**
   * Filter value
   * @param mixed $value
   * @param string $action
   * @param mixed ...$args
   * @return mixed
   */
  public function get_filter( mixed $value, string $action, mixed ...$args ): mixed {
    $this->load_actions();
    if ( isset( $this->actions[ $action ] ) ) {
      $actions = $this->actions[ $action ];
      $priorities = array_keys( $actions );
      do {
        $offset = current( $priorities );
        $priority = $actions[ $offset ];
        foreach ( $priority as $callback ) {
          $value = $callback( $value, ...$args );
        }
      } while ( next( $priorities ) !== false );
    }
    return $value;
  }
  
  /**
   * Load plugins and theme functions
   * @return void
   */
  public function load_actions(): void {
    foreach ( $this->data( 'installed' ) as $plugin ) {
      $plugin = $this->root( 'plugins/' . $plugin . '/plugin.php' );
      if ( is_file( $plugin ) ) include_once ( $plugin );
    }
    $functions = $this->theme( 'functions.php' );
    if ( is_file( $functions ) ) include_once ( $functions );
  }
  
  /**
   * Set an alert for admin
   * @param string $message
   * @param string $type
   * @return void
   */
  public function alert( string $message, string $type = 'info' ): void {
    if ( isset( $_SESSION[ 'alerts' ] ) ) {
      foreach ( $_SESSION[ 'alerts' ] as $alert ) {
        if ( $alert === array( 'message' => $message, 'type' => $type ) ) {
          return;
        }
      }
    }
    $_SESSION[ 'alerts' ][] = array( 'message' => $message, 'type' => $type );
  }
  
  /**
   * Get all admin alerts
   * @return void
   */
  public function alerts(): void {
    if ( isset( $_SESSION[ 'alerts' ] ) ) {
      $result = '<div class="ss-container ss-responsive ss-mobile ss-w-7 ss-mx-auto">';
      foreach ( $_SESSION[ 'alerts' ] as $alert ) {
        $result .= '<div class="ss-alert ss-' . $alert[ 'type' ] . ' ss-anim-jello ss-responsive">';
        $result .= '  <p>' . $alert[ 'message' ] . '</p>';
        $result .= '</div>';
      }
      $result .= '</div>';
      unset( $_SESSION[ 'alerts' ] );
      echo $result;
    }
  }
  
  /**
   * Get page field value
   * @param string $index
   * @param ?string $slug
   * @return mixed
   */
  public function page( string $index, ?string $slug = null ): mixed {
    $slug ??= $this->page;
    if ( $this->is_page( $slug ) ) {
      $page = $this->data( 'pages' )[ $slug ];
      if ( isset( $page[ $index ] ) ) {
        $value = $page[ $index ];
        $args = array( $index, $slug, $page );
        return $this->get_filter( $value, 'page', ...$args );
      }
    }
    return $this->get_filter( null, 'page', $index, $slug, array() );
  }
  
  /**
   * Create new page
   * @param string $slug
   * @param array $details
   * @return bool
   */
  public function create_page( string $slug, array $details ): bool {
    $this->get_action( 'create_page', $slug, $details );
    $this->database[ 'pages' ][ $slug ] = $details;
    return $this->save();
  }
  
  /**
   * Modify page
   * @param string $slug
   * @param string $permalink
   * @param array $updates
   * @return bool
   */
  public function update_page( string $slug, string $permalink, array $updates ): bool {
    $this->get_action( 'update_page', $slug, $permalink, $updates );
    $updates = array_merge( $this->data( 'pages' )[ $slug ], $updates );
    $this->database[ 'pages' ][ $slug ] = $updates;
    $data = json_encode( $this->database, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE );
    $data = str_replace( '"' . addcslashes( $slug, '\/' ) . '":', '"' . addcslashes( $permalink, '\/' ) . '":', $data );
    return $this->save( json_decode( $data, true ) );
  }
  
  /**
   * Delete page
   * @param string $slug
   * @return bool
   */
  public function delete_page( string $slug ): bool {
    $this->get_action( 'delete_page', $slug );
    unset( $this->database[ 'pages' ][ $slug ] );
    return $this->save();
  }
  
  /**
   * Tells whether a page exists
   * @param string $slug
   * @return bool
   */
  public function is_page( string $slug ): bool {
    return isset( $this->data( 'pages' )[ $slug ] );
  }
  
  /**
   * Upload media file
   * @param ?string $msg
   * @param ?string $basename
   * @return bool
   */
  public function upload_media( ?string &$msg = null, ?string &$basename = null ): bool {
    if ( ! isset( $_FILES[ 'file' ][ 'error' ] ) || is_array( $_FILES[ 'file' ][ 'error' ] ) ) {
      $msg = 'Invalid parameters';
      return false;
    }
    switch ( $_FILES[ 'file' ][ 'error' ] ) {
      case UPLOAD_ERR_OK:
        break;
      case UPLOAD_ERR_NO_FILE:
        $msg = 'No file has been sent';
        return false;
        break;
      case UPLOAD_ERR_INI_SIZE:
      case UPLOAD_ERR_FORM_SIZE:
        $msg = 'File too large';
        return false;
        break;
      default:
        $msg = 'An unexpected error occurred';
        return false;
        break;
    }
    $tmp_name = $_FILES[ 'file' ][ 'tmp_name' ];
    $finfo = new finfo( FILEINFO_MIME_TYPE );
    $type = $finfo->file( $tmp_name );
    $types = $this->_l( 'media_mime',
      array(
        'application/octet-stream',
        'application/ogg',
        'application/pdf',
        'application/photoshop',
        'application/rar',
        'application/vnd.ms-excel',
        'application/vnd.ms-powerpoint',
        'application/vnd.ms-word',
        'application/vnd.oasis.opendocument.spreadsheet',
        'application/vnd.oasis.opendocument.text',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/zip',
        'audio/mp4',
        'audio/mpeg',
        'image/avif',
        'image/gif',
        'image/jpeg',
        'image/png',
        'image/svg+xml',
        'image/vnd.microsoft.icon',
        'image/webp',
        'image/x-icon',
        'text/css',
        'text/html',
        'text/plain',
        'text/x-asm',
        'video/avi',
        'video/mp4',
        'video/mpeg',
        'video/ogg',
        'video/quicktime',
        'video/webm',
        'video/x-flv',
        'video/x-matroska',
        'video/x-ms-wmv'
      )
    );
    if ( ! in_array( $type, $types ) ) {
      $msg = 'File format not allowed';
      return false;
    }
    $name = $this->esc_slug( $_FILES[ 'file' ][ 'name' ] );
    $basename = basename( empty( $basename ) ? strip_tags( $name ) : $basename );
    $extension = explode( '.', $basename );
    $extension = strtolower( end( $extension ) );
    $extensions = $this->_l( 'media_extension',
      array(
        'avi',
        'avif',
        'css',
        'doc',
        'docx',
        'flv',
        'gif',
        'htm',
        'html',
        'ico',
        'jpeg',
        'jpg',
        'kdbx',
        'm4a',
        'mkv',
        'mov',
        'mp3',
        'mp4',
        'mpg',
        'ods',
        'odt',
        'ogg',
        'ogv',
        'pdf',
        'png',
        'ppt',
        'pptx',
        'psd',
        'rar',
        'svg',
        'txt',
        'xls',
        'xlsx',
        'webm',
        'webp',
        'wmv',
        'zip'
      )
    );
    if ( ! in_array( $extension, $extensions ) ) {
      if ( $extension !== $basename || 'text/plain' !== $type ) {
        $msg = 'File extension not allowed';
        return false;
      }
    }
    if ( move_uploaded_file( $tmp_name, $this->root( 'media/' . $basename ) ) ) {
      $msg = sprintf( 'File <b>%s</b> has been uploaded successfully', $basename );
      $this->get_action( 'upload_media', $basename );
      return true;
    }
    $msg = 'Failed to move uploaded file';
    return false;
  }
  
  /**
   * Delete media file
   * @param string $media
   * @return bool
   */
  public function delete_media( string $media ): bool {
    if ( in_array( $media, $this->medias ) ) {
      $this->get_action( 'delete_media', $media );
      return unlink( $this->root( 'media/' . $media ) );
    }
    return false;
  }
  
  /**
   * Install a plugin
   * @param string $plugin
   * @return bool
   */
  public function install( string $plugin ): bool {
    if ( in_array( $plugin, $this->plugins ) ) {
      if ( ! $this->installed( $plugin ) ) {
        $this->database[ 'installed' ][] = $plugin;
        $this->get_action( 'install', $plugin );
        return $this->save();
      }
    }
    return false;
  }
  
  /**
   * Uninstall a plugin
   * @param string $plugin
   * @return bool
   */
  public function uninstall( string $plugin ): bool {
    if ( $this->installed( $plugin ) ) {
      $this->get_action( 'uninstall', $plugin );
      $index = array_search( $plugin, $this->data( 'installed' ) );
      unset( $this->database[ 'installed' ][ $index ] );
      return $this->save();
    }
    return false;
  }
  
  /**
   * Tells whether a plugin is installed
   * @param string $plugin
   * @return bool
   */
  public function installed( string $plugin ): bool {
    return in_array( $plugin, $this->data( 'installed' ) );
  }
  
  /**
   * Slugify text
   * @param string $text
   * @return string
   */
  public function slugify( string $text ): string {
    $slug = preg_replace( '/[^\\pL\d]+/u', '-', $text );
    $slug = preg_replace( '/[^-\w]+/', '', $slug );
    $slug = substr( $slug, 0, 50 );
    $slug = strtolower( $slug );
    $slug = trim( $slug, '-' );
    $pages = $this->data( 'pages' );
    $pages = array_keys( $pages );
    $taken = $this->_l( 'slug_taken', $pages );
    $taken[] = $this->admin_url();
    if ( in_array( $slug, $taken ) || file_exists( $this->root( $slug ) ) ) {
      $slug = ( $slug . '-' . bin2hex( random_bytes(2) ) );
    }
    $slug = ( empty( $slug ) ? bin2hex( random_bytes(2) ) : $slug );
    return $this->_( $slug, 'slugify', $text );
  }
  
  /**
   * Redirections
   * @param string $location
   * @return void
   */
  public function go( string $location = '' ): void {
    $this->get_action( 'go', $location );
    $location = $this->url( $location );
    if ( ! headers_sent() ) {
      header( 'Location: ' . $location, true, 302 );
      exit;
    }
    exit( '<meta http-equiv="refresh" content="0; url=' . $location . '">' );
  }
  
  /**
   * Sanitize custom permalink
   * @param string $slug
   * @param string $alt
   * @return string
   */
  public function esc_slug( string $slug, string $alt = '' ): string {
    $slug = stripslashes( $slug );
    $slug = filter_var( $slug, FILTER_SANITIZE_URL );
    $slug = str_replace( array( '?', '&', '#', '"' ), '', $slug );
    $slug = ( ! empty( $slug ) ? $slug : $alt );
    return trim( ltrim( $slug, './' ) );
  }
  
  /**
   * Sanitize text
   * @param string|array|null $text
   * @param bool $trim
   * @return string
   */
  public function esc( string | array | null $text, bool $trim = true ): string {
    if ( is_array( $text ) ) {
      $text = null;
    }
    $text ??= '';
    $text = stripslashes( $text );
    $text = htmlspecialchars( $text );
    return ( $trim ? trim( $text ) : $text );
  }
  
  /**
   * Validate csrf token
   * @param ?string $location
   * @param bool $post
   * @return void
   */
  public function auth( ?string $location = null, bool $post = true ): void {
    $location ??= $this->page;
    $token = ( $post ? ( $_POST[ 'token' ] ?? '' ) : ( $_GET[ 'token' ] ?? '' ) );
    if ( ! hash_equals( $this->token(), $token ) ) {
      $this->get_action( 'token_error', $token );
      $this->alert( 'Invalid token, please try again.', 'error' );
      $this->go( $location );
    }
  }
  
  /**
   * Admin backend handler
   * @return void
   */
  public function admin(): void {
    global $layout, $action, $page;
    $page = $this->esc( $_GET[ 'page' ] ?? '' );
    $action = ( $_GET[ 'action' ] ?? '' );
    $layout = array( 'title' => '', 'content' => '' );
    if ( ! $this->logged_in ) {
      $this->get_action( 'login' );
      $layout[ 'title' ] = 'Login';
      $layout[ 'content' ] = '
      <form action="' . $this->admin_url( abs: true ) . '" method="post" class="ss-py-4">
        <h2 class="ss-monospace">Login to your website</h2>
        <label for="username" class="ss-label">Username <span class="ss-red">*</span></label>
        <input type="text" id="username" name="username" class="ss-input ss-mobile ss-w-5 ss-mx-auto" required>
        <label for="password" class="ss-label">Password <span class="ss-red">*</span></label>
        <input type="password" id="password" name="password" class="ss-input ss-mobile ss-w-5 ss-mx-auto" required>
        ' . $this->get_action( 'form' ) . '
        <input type="hidden" name="token" value="' . $this->token() . '">
        <input type="submit" name="login" value="Login" class="ss-btn ss-mobile ss-w-4">
      </form>';
      if ( isset( $_POST[ 'login' ] ) ) {
        $this->auth();
        $this->get_action( 'on_login' );
        $username = ( $_POST[ 'username' ] ?? '' );
        $password = ( $_POST[ 'password' ] ?? '' );
        if ( hash_equals( $this->get( 'username' ), $username ) && password_verify( $password, $this->get( 'password' ) ) ) {
          session_regenerate_id( true );
          $_SESSION[ 'logged_in' ] = true;
          $_SESSION[ 'root' ] = $this->root;
          $this->get_action( 'login_success' );
          $this->go( $this->admin_url() );
        }
        $this->get_action( 'login_error', $username, $password );
        $this->alert( 'Incorrect username or password, please try again.', 'error' );
        $this->go( $this->admin_url() );
      }
    } else {
      $this->get_action( 'admin' );
      switch ( $page ) {
        case 'create':
          $layout[ 'title' ] = 'Create Page';
          $layout[ 'content' ] = '
          <form action="' . $this->admin_url( '?page=create', true ) . '" method="post" enctype="multipart/form-data">
            <label for="type" class="ss-label">Type</label>
            <select id="type" name="type" class="ss-select ss-mobile ss-w-6 ss-mx-auto">
              <option value="post"' . ( ( $_POST[ 'type' ] ?? '' ) === 'post' ? ' selected' : '' ) . '>Post</option>
              <option value="page"' . ( ( $_POST[ 'type' ] ?? '' ) === 'page' ? ' selected' : '' ) . '>Page</option>
              ' . $this->get_action( 'type' ) . '
            </select>
            <label for="title" class="ss-label">Title <span class="ss-red">*</span></label>
            <input type="text" id="title" name="title" placeholder="Page title" value="' . $this->esc( $_POST[ 'title' ] ?? '' ) . '" class="ss-input ss-mobile ss-w-6 ss-mx-auto" required>
            <label for="descr" class="ss-label">Description</label>
            <textarea rows="5" id="descr" name="descr" placeholder="Page description" class="ss-textarea ss-mobile ss-w-6 ss-mx-auto">' . $this->esc( $_POST[ 'descr' ] ?? '' ) . '</textarea>
            <label for="keywords" class="ss-label">Keywords</label>
            <input type="text" id="keywords" name="keywords" placeholder="Keywords, for, seo" value="' . $this->esc( $_POST[ 'keywords' ] ?? '' ) . '" class="ss-input ss-mobile ss-w-6 ss-mx-auto">
            <label for="content" class="ss-label">Content</label>
            <textarea rows="20" id="content" name="content" placeholder="Start writing ✍" class="ss-textarea ss-mobile ss-w-6 ss-mx-auto ss-responsive">' . $this->esc( $_POST[ 'content' ] ?? '' ) . '</textarea>
            <label for="permalink" class="ss-label">Permalink</label>
            <input type="text" id="permalink" name="permalink" placeholder="custom/permalink.html" value="' . $this->esc( $_POST[ 'permalink' ] ?? '' ) . '" class="ss-input ss-mobile ss-w-6 ss-mx-auto">
            <label for="tpl" class="ss-label">Template</label>
            <select id="tpl" name="tpl" class="ss-select ss-mobile ss-w-6 ss-mx-auto">
              <option value="theme.php">Default</option>';
          $templates = $this->_l( 'tpl' );
          foreach ( $templates as $tpl ) {
            $tpl = $this->esc( $tpl );
            $layout[ 'content' ] .= '<option value="' . $tpl . '"' . ( ( $_POST[ 'tpl' ] ?? '' ) === $tpl ? ' selected' : '' ) . '>' . $tpl . '</option>';
          }
          $layout[ 'content' ] .= '
            </select>
            <label for="thumb" class="ss-label">Thumbnail</label>
            <select id="thumb" name="thumb" class="ss-select ss-mobile ss-w-6 ss-mx-auto">
              <option value selected>Choose a thumbnail</option>';
          foreach ( $this->medias as $media ) {
            $ext = pathinfo( $media, PATHINFO_EXTENSION );
            $extensions = $this->_l( 'thumb_ext', array( 'avif', 'gif', 'jpeg', 'jpg', 'png', 'webp' ) );
            if ( in_array( $ext, $extensions ) ) {
              $layout[ 'content' ] .= '<option value="' . $media . '"' . ( ( $_POST[ 'thumb' ] ?? '' ) === $media ? ' selected' : '' ) . '>' . $media . '</option>';
            }
          }
          $layout[ 'content' ] .= '
            </select>
            <label for="date" class="ss-label">Date <span class="ss-red">*</span></label>
            <input type="datetime-local" id="date" name="date" step="any" value="' . $this->esc( $_POST[ 'date' ] ?? '' ) . '" class="ss-select ss-mobile ss-w-6 ss-mx-auto" required>
            <label for="pub" class="ss-label">Publish</label>
            <select id="pub" name="pub" class="ss-select ss-mobile ss-w-6 ss-mx-auto">
              <option value="true"' . ( ( $_POST[ 'pub' ] ?? '' ) === 'true' ? ' selected' : '' ) . '>Yes</option>
              <option value="false"' . ( ( $_POST[ 'pub' ] ?? '' ) === 'false' ? ' selected' : '' ) . '>No</option>
            </select>
            ' . $this->get_action( 'form' ) . '
            <input type="hidden" name="token" value="' . $this->esc( $_POST[ 'token' ] ?? $this->token() ) . '">
            <input type="submit" name="create" value="Create" class="ss-btn ss-mobile ss-w-5">
          </form>';
          if ( isset( $_POST[ 'create' ] ) ) {
            $this->auth();
            $this->get_action( 'on_create' );
            $_POST[ 'pub' ] = filter_input( INPUT_POST, 'pub', FILTER_VALIDATE_BOOL );
            $permalink = $this->esc_slug( $_POST[ 'permalink' ], $this->slugify( $_POST[ 'title' ] ) );
            unset( $_POST[ 'permalink' ], $_POST[ 'token' ], $_POST[ 'create' ] );
            $taken = $this->_l( 'slug_taken' );
            $taken[] = $this->admin_url();
            if ( $this->is_page( $permalink ) || in_array( $permalink, $taken ) ) {
              $this->alert( 'Page with that permalink already exist, try <b>' . $this->slugify( $permalink ) . '</b> instead.', 'error' );
              break;
            } else if ( file_exists( $this->root( $permalink ) ) ) {
              $this->alert( 'Directory/File with that permalink already exist, try <b>' . $this->slugify( $permalink ) . '</b> instead.', 'error' );
              break;
            }
            if ( $this->create_page( $permalink, $_POST ) ) {
              $this->get_action( 'create_success', $permalink );
              $this->alert( 'Page created successfully' . ( $_POST[ 'pub' ] ? sprintf( ', click <a href="%s" target="_blank" class="ss-dotted">here</a> to preview.', $this->url( $permalink ) ) : '.' ), 'success' );
              $this->go( $this->admin_url() );
            }
            $this->get_action( 'create_error', $permalink, $_POST );
            $this->alert( 'Page not created, please try again.', 'error' );
            break;
          }
          break;
        case 'delete':
          $layout[ 'title' ] = 'Delete Page';
          $layout[ 'content' ] = '
          <form action="' . $this->admin_url( '?page=delete', true ) . '" method="post">
            <label for="pages" class="ss-label">Pages <span class="ss-red">*</span></label>
            <select id="pages" name="pages[]" class="ss-select ss-mobile ss-w-6 ss-mx-auto" multiple required>';
          foreach ( $this->data( 'pages' ) as $slug => $details ) {
            if ( intval( $slug ) !== 404 && $slug !== 'home' ) {
              $layout[ 'content' ] .= '<option value="' . $this->esc( $slug ) . '">' . $this->esc( $details[ 'title' ] . ' (' . $slug . ')' ) . '</option>';
            }
          }
          $layout[ 'content' ] .= '
            </select>
            ' . $this->get_action( 'form' ) . '
            <input type="hidden" name="token" value="' . $this->token() . '">
            <input type="submit" name="delete" value="Delete" class="ss-btn ss-error ss-mobile ss-w-5">
          </form>';
          if ( isset( $_POST[ 'delete' ] ) ) {
            $this->auth();
            $this->get_action( 'on_delete' );
            $pages = ( $_POST[ 'pages' ] ?? array() );
            foreach ( $pages as $page ) {
              if ( $this->delete_page( $page ) ) {
                $this->get_action( 'delete_success', $page );
                $this->alert( sprintf( 'Page <b>%s</b> has been deleted successfully.', $page ), 'success' );
              } else {
                $this->get_action( 'delete_error', $page );
                $this->alert( sprintf( 'Page <b>%s</b> was not deleted, please try again.', $page ), 'error' );
              }
            }
            $this->go( $this->admin_url( '?page=delete' ) );
          }
          break;
        case 'update':
          $layout[ 'title' ] = 'Update Page';
          if ( ! $this->is_page( $action ) ) {
            $layout[ 'content' ] = '
            <form action="' . $this->admin_url( abs: true ) . '" method="get">
              <input type="hidden" name="page" value="update">
              <label for="page" class="ss-label">Choose Page <span class="ss-red">*</span></label>
              <select id="page" name="action" class="ss-select ss-mobile ss-w-6 ss-mx-auto" required>
                <option value selected disabled>Choose page</option>';
              foreach ( $this->data( 'pages' ) as $slug => $details ) {
                $layout[ 'content' ] .= '<option value="' . $this->esc( $slug ) . '">' . $this->esc( $details[ 'title' ] . ' (' . $slug . ')' ) . '</option>';
              }
              $layout[ 'content' ] .= '
              </select>
              ' . $this->get_action( 'form' ) . '
              <input type="submit" value="Select" class="ss-btn ss-mobile ss-w-5">
            </form>';
          } else {
            $data = ( empty( $_POST ) ? $this->data( 'pages' )[ $action ] : $_POST );
            $data[ 'pub' ] = ( $data[ 'pub' ] === 'true' || $data[ 'pub' ] === true ? true : false );
            $layout[ 'content' ] = '
            <form action="' . $this->admin_url( '?page=update&action=' . $action, true ) . '" method="post" enctype="multipart/form-data">
              <label for="type" class="ss-label">Type</label>
              <select id="type" name="type" class="ss-select ss-mobile ss-w-6 ss-mx-auto">
                <option value="post"' . ( $data[ 'type' ] === 'post' ? ' selected' : '' ) . '>Post</option>
                <option value="page"' . ( $data[ 'type' ] === 'page' ? ' selected' : '' ) . '>Page</option>
                ' . $this->get_action( 'type' ) . '
              </select>
              <label for="title" class="ss-label">Title <span class="ss-red">*</span></label>
              <input type="text" id="title" name="title" placeholder="Page title" value="' . $this->esc( $data[ 'title' ] ) . '" class="ss-input ss-mobile ss-w-6 ss-mx-auto" required>
              <label for="descr" class="ss-label">Description</label>
              <textarea rows="5" id="descr" name="descr" placeholder="Page description" class="ss-textarea ss-mobile ss-w-6 ss-mx-auto">' . $this->esc( $data[ 'descr' ] ) . '</textarea>
              <label for="keywords" class="ss-label">Keywords</label>
              <input type="text" id="keywords" name="keywords" placeholder="Keywords, for, seo" value="' . $this->esc( $data[ 'keywords' ] ) . '" class="ss-input ss-mobile ss-w-6 ss-mx-auto">
              <label for="content" class="ss-label">Content</label>
              <textarea rows="20" id="content" name="content" placeholder="Start writing ✍" class="ss-textarea ss-mobile ss-w-6 ss-mx-auto">' . $this->esc( $data[ 'content' ] ) . '</textarea>
              <label for="permalink" class="ss-label">Permalink</label>
              <input type="text" id="permalink" name="permalink" placeholder="custom/permalink.html" value="' . $this->esc( $data[ 'permalink' ] ?? $action ) . '" class="ss-input ss-mobile ss-w-6 ss-mx-auto">
              <label for="tpl" class="ss-label">Template</label>
              <select id="tpl" name="tpl" class="ss-select ss-mobile ss-w-6 ss-mx-auto">
                <option value="theme.php">Default</option>';
            $templates = $this->_l( 'tpl' );
            foreach ( $templates as $tpl ) {
              $tpl = $this->esc( $tpl );
              $layout[ 'content' ] .= '<option value="' . $tpl . '"' . ( $data[ 'tpl' ] === $tpl ? ' selected' : '' ) . '>' . $tpl . '</option>';
            }
            $layout[ 'content' ] .= '
              </select>
              <label for="thumb" class="ss-label">Thumbnail</label>
              <select id="thumb" name="thumb" class="ss-select ss-mobile ss-w-6 ss-mx-auto">
                <option value>Choose a thumbnail</option>';
            foreach ( $this->medias as $media ) {
              $ext = pathinfo( $media, PATHINFO_EXTENSION );
              $extensions = $this->_l( 'thumb_ext', array( 'avif', 'gif', 'jpg', 'jpeg', 'png', 'webp' ) );
              if ( in_array( $ext, $extensions ) ) {
                $layout[ 'content' ] .= '<option value="' . $media . '"' . ( $media === $data[ 'thumb' ] ? ' selected' : '' ) . '>' . $media . '</option>';
              }
            }
            $layout[ 'content' ] .= '
              </select>
              <label for="date" class="ss-label">Date <span class="ss-red">*</span></label>
              <input type="datetime-local" id="date" name="date" step="any" value="' . $this->esc( $data[ 'date' ] ) . '" class="ss-select ss-mobile ss-w-6 ss-mx-auto" required>
              <label for="pub" class="ss-label">Publish</label>
              <select id="pub" name="pub" class="ss-select ss-mobile ss-w-6 ss-mx-auto">
                <option value="true"' . ( $data[ 'pub' ] ? ' selected' : '' ) . '>Yes</option>
                <option value="false"' . ( $data[ 'pub' ] ? '' : ' selected' ) . '>No</option>
              </select>
              ' . $this->get_action( 'form' ) . '
              <input type="hidden" name="token" value="' . $this->token() . '">
              <div class="ss-btn-group ss-mobile ss-w-5">
                <input type="submit" name="update" value="Update" class="ss-btn ss-w-5">
                <a href="' . $this->admin_url( '?page=update', true ) . '" class="ss-btn ss-inverted ss-w-5">Cancel</a>
              </div>
            </form>';
          }
          if ( isset( $_POST[ 'update' ] ) ) {
            $this->auth();
            $this->get_action( 'on_update' );
            $_POST[ 'pub' ] = filter_input( INPUT_POST, 'pub', FILTER_VALIDATE_BOOL );
            $update = $this->esc_slug( $_POST[ 'permalink' ], $action );
            unset( $_POST[ 'permalink' ], $_POST[ 'token' ], $_POST[ 'update' ] );
            $taken = $this->_l( 'slug_taken' );
            $taken[] = $this->admin_url();
            if ( ( $action !== $update ) && $this->is_page( $update ) || in_array( $update, $taken ) ) {
              $this->alert( 'Page with that permalink already exist, try <b>' . $this->slugify( $update ) . '</b> instead.', 'error' );
              break;
            } else if ( file_exists( $this->root( $update ) ) ) {
              $this->alert( 'Directory/File with the given custom permalink already exist, try <b>' . $this->slugify( $update ) . '</b> instead.', 'error' );
              break;
            }
            $update = ( intval( $action ) === 404 || $action === 'home' ? $action : $update );
            if ( $this->update_page( $action, $update, $_POST ) ) {
              $this->get_action( 'update_success', $action );
              $this->alert( 'Page updated successfully' . ( $_POST[ 'pub' ] ? sprintf( ', click <a href="%s" target="_blank" class="ss-dotted">here</a> to preview.', $this->url( $update ) ) : '.' ), 'success' );
              $this->go( $this->admin_url( '?page=update' ) );
            }
            $this->get_action( 'update_error', $action, $_POST );
            $this->alert( 'Page not updated, please try again.', 'error' );
            break;
          }
          break;
        case 'media':
          $layout[ 'title' ] = 'Media';
          $layout[ 'content' ] = '
          <form action="' . $this->admin_url( '?page=media', true ) . '" method="post" enctype="multipart/form-data" class="ss-py-4">
            <label for="file" class="ss-label">File <span class="ss-red">*</span></label>
            <input type="file" id="file" name="file" class="ss-input ss-mobile ss-w-6 ss-mx-auto ss-ovf-hidden" required>
            <small class="ss-label">Max upload size: ' . ini_get( 'upload_max_filesize' ) . '</small>
            ' . $this->get_action( 'form' ) . '
            <input type="hidden" name="token" value="' . $this->token() . '">
            <input type="submit" name="upload" value="Upload" class="ss-btn ss-mobile ss-w-5">
          </form>
          <hr class="ss-hr">
          <ul class="ss-list ss-fieldset ss-mobile ss-w-6 ss-mx-auto ss-py-4">
            <li class="ss-bd-none">
              <h3 class="ss-monospace">Uploaded Files</h3>
              <hr class="ss-hr">
            </li>';
          foreach ( $this->medias as $media ) {
            $layout[ 'content' ] .= '
              <li class="ss-responsive">
                ' . $this->get_action( 'media_list_top', $media ) . '
                <h4 class="ss-monospace ss-responsive">' . $media . '</h4>
                <div class="ss-btn-group ss-full ss-my-4">
                  <a href="' . $this->url( 'media/' . $media ) . '" target="_blank" class="ss-btn ss-w-5">View</a>
                  <a href="' . $this->admin_url( '?page=media&action=delete&file=' . $media . '&token=' . $this->token(), true ) . '" class="ss-btn ss-error ss-w-5">Delete</a>
                </div>
                ' . $this->get_action( 'media_list_end', $media ) . '
              </li>';
          }
          $layout[ 'content' ] .= '</ul>';
          if ( $action === 'delete' ) {
            $this->auth( post: false );
            $this->get_action( 'on_media' );
            $file = ( $_GET[ 'file' ] ?? '' );
            if ( $this->delete_media( $file ) ) {
              $this->alert( sprintf( 'File <b>%s</b> has been deleted successfully.', $file ), 'success' );
              $this->go( $this->admin_url( '?page=media' ) );
            }
            $this->alert( sprintf( 'File <b>%s</b> was not deleted, please try again.', $file ), 'error' );
            $this->go( $this->admin_url( '?page=media' ) );
          } else if ( isset( $_POST[ 'upload' ] ) ) {
            $this->auth();
            $this->get_action( 'on_media' );
            if ( $this->upload_media( $message ) ) {
              $this->alert( $message . '.', 'success' );
              $this->go( $this->admin_url( '?page=media' ) );
            }
            $this->alert( $message . ', please try again.', 'error' );
            $this->go( $this->admin_url( '?page=media' ) );
          }
          break;
        case 'plugins':
          $layout[ 'title' ] = 'Plugins';
          $layout[ 'content' ] = '
          <ul class="ss-list ss-fieldset ss-mobile ss-w-6 ss-mx-auto">';
          foreach ( $this->plugins as $plugin ) {
            $layout[ 'content' ] .= '
            <li class="ss-responsive">
              ' . $this->get_action( 'plugin_list_top', $plugin ) . '
              <h4 class="ss-monospace ss-responsive">' . ucwords( str_replace( '-', ' ', $plugin ) ) . '</h4>
              <div class="ss-btn-group ss-full ss-my-4">
                <a href="' . $this->admin_url( '?page=plugins&action=' . ( $this->installed( $plugin ) ? 'uninstall' : 'install' ) . '&plugin=' . $plugin . '&token=' . $this->token(), true ) . '" class="ss-btn ss-w-5">' . ( $this->installed( $plugin ) ? 'Uninstall' : 'Install' ) . '</a>
                <a ' . ( $this->installed( $plugin ) ? 'href="' . $this->admin_url( '?page=' . $plugin, true ) . '" ' : '' ) . 'class="ss-btn ss-inverted ss-w-5' . ( $this->installed( $plugin ) ? '' : ' ss-disabled' ) . '">Configure</a>
              </div>
              ' . $this->get_action( 'plugin_list_end', $plugin ) . '
            </li>';
          }
          $layout[ 'content' ] .= '</ul>';
          if ( isset( $_GET[ 'plugin' ] ) ) {
            $this->auth( post: false );
            $this->get_action( 'on_plugin' );
            $plugin = ( $_GET[ 'plugin' ] ?? '' );
            if ( $action === 'install' ) {
              if ( $this->install( $plugin ) ) {
                $this->alert( sprintf( 'Plugin <b>%s</b> has been installed successfully.', ucwords( str_replace( '-', ' ', $plugin ) ) ), 'success' );
                $this->go( $this->admin_url( '?page=plugins' ) );
              }
              $this->alert( sprintf( 'Plugin <b>%s</b> was not installed, please try again.', ucwords( str_replace( '-', ' ', $plugin ) ) ), 'error' );
              $this->go( $this->admin_url( '?page=plugins' ) );
            } else if ( $action === 'uninstall' ) {
              if ( $this->uninstall( $plugin ) ) {
                $this->alert( sprintf( 'Plugin <b>%s</b> has been uninstalled successfully.', ucwords( str_replace( '-', ' ', $plugin ) ) ), 'success' );
                $this->go( $this->admin_url( '?page=plugins' ) );
              }
              $this->alert( sprintf( 'Plugin <b>%s</b> was not uninstalled, please try again.', ucwords( str_replace( '-', ' ', $plugin ) ) ), 'error' );
              $this->go( $this->admin_url( '?page=plugins' ) );
            }
          }
          break;
        case 'themes':
          $layout[ 'title' ] = 'Themes';
          $layout[ 'content' ] = '
          <ul class="ss-list ss-fieldset ss-mobile ss-w-6 ss-mx-auto">';
          foreach ( $this->themes as $theme ) {
            $layout[ 'content' ] .= '
            <li class="ss-responsive">
              ' . $this->get_action( 'theme_list_top', $theme ) . '
              <h4 class="ss-monospace ss-responsive">' . ucwords( str_replace( '-', ' ', $theme ) ) . '</h4>
              <div class="ss-btn-group ss-full ss-my-4">
                <a ' . ( $this->get( 'theme' ) === $theme ? '' : 'href="' . $this->admin_url( '?page=themes&action=activate&theme=' . $theme . '&token=' . $this->token(), true ) . '" ' ) . 'class="ss-btn ss-w-5' . ( $this->get( 'theme' ) === $theme ? ' ss-disabled' : '' ) . '">Activate</a>
                <a ' . ( $this->get( 'theme' ) === $theme ? 'href="' . $this->admin_url( '?page=' . $theme, true ) . '" ' : '' ) . 'class="ss-btn ss-inverted ss-w-5' . ( $this->get( 'theme' ) === $theme ? '' : ' ss-disabled' ) . '">Configure</a>
              </div>
              ' . $this->get_action( 'theme_list_end', $theme ) . '
            </li>';
          }
          $layout[ 'content' ] .= '</ul>';
          if ( isset( $_GET[ 'theme' ] ) ) {
            $this->auth( post: false );
            $this->get_action( 'on_theme' );
            $theme = ( $_GET[ 'theme' ] ?? '' );
            if ( $action === 'activate' ) {
              if ( in_array( $theme, $this->themes ) ) {
                if ( $this->set( $theme, 'theme' ) ) {
                  $this->get_action( 'change_theme', $theme );
                  $this->alert( sprintf( 'Theme <b>%s</b> has been activated successfully.', ucwords( str_replace( '-', ' ', $theme ) ) ), 'success' );
                  $this->go( $this->admin_url( '?page=themes' ) );
                }
              }
              $this->alert( 'Failed to update theme, please try again.', 'error' );
              $this->go( $this->admin_url( '?page=themes' ) );
            }
          }
          break;
        case 'logout':
          $this->auth( post: false );
          unset( $_SESSION[ 'logged_in' ], $_SESSION[ 'root' ], $_SESSION[ 'token' ], $_SESSION[ 'alerts' ] );
          $this->get_action( 'logout' );
          $this->go();
          break;
        case 'settings':
          $layout[ 'title' ] = 'Settings';
          $layout[ 'content' ] = '
          <form action="' . $this->admin_url( '?page=settings', true ) . '" method="post" enctype="multipart/form-data" class="ss-py-4">
            <label for="lang" class="ss-label">Language Code <span class="ss-red">*</span></label>
            <input type="text" id="lang" name="lang" placeholder="en" value="' . $this->esc( $this->get( 'lang' ) ) . '" class="ss-input ss-mobile ss-w-6 ss-mx-auto" required>
            <label for="title" class="ss-label">Title <span class="ss-red">*</span></label>
            <input type="text" id="title" name="title" placeholder="Site title" value="' . $this->esc( $this->get( 'title' ) ) . '" class="ss-input ss-mobile ss-w-6 ss-mx-auto" required>
            <label for="subtitle" class="ss-label">Subtitle</label>
            <input type="text" id="subtitle" name="subtitle" placeholder="Site subtitle" value="' . $this->esc( $this->get( 'subtitle' ) ) . '" class="ss-input ss-mobile ss-w-6 ss-mx-auto">
            <label for="keywords" class="ss-label">Keywords</label>
            <input type="text" id="keywords" name="keywords" placeholder="Keywords, for, seo" value="' . $this->esc( $this->get( 'keywords' ) ) . '" class="ss-input ss-mobile ss-w-6 ss-mx-auto">
            <label for="descr" class="ss-label">Description</label>
            <textarea rows="10" id="descr" name="descr" placeholder="Site description" class="ss-textarea ss-mobile ss-w-6 ss-mx-auto">' . $this->esc( $this->get( 'descr' ) ) . '</textarea>
            <label for="email" class="ss-label">Email</label>
            <input type="email" id="email" name="email" placeholder="mail@example.com" value="' . $this->esc( $this->get( 'email' ) ) . '" class="ss-input ss-mobile ss-w-6 ss-mx-auto">
            <label for="username" class="ss-label">Username <span class="ss-red">*</span></label>
            <input type="text" id="username" name="username" placeholder="John Doe" value="' . $this->esc( $this->get( 'username' ) ) . '" class="ss-input ss-mobile ss-w-6 ss-mx-auto" required>
            <label for="url" class="ss-label">Site URL <span class="ss-red">*</span></label>
            <input type="url" id="url" name="url" placeholder="' . $this->get( 'url' ) . '" value="' . $this->esc( $this->get( 'url' ) ) . '" class="ss-input ss-mobile ss-w-6 ss-mx-auto" required>
            <label for="admin" class="ss-label">Admin URL <span class="ss-red">*</span></label>
            <input type="text" id="admin" name="admin" placeholder="example/' . bin2hex( random_bytes(3) ) . '/admin" value="' . $this->esc( $this->admin_url() ) . '" class="ss-input ss-mobile ss-w-6 ss-mx-auto" required>
            <label for="blog" class="ss-label">Enable Blog</label>
            <select id="blog" name="blog" class="ss-select ss-mobile ss-w-6 ss-mx-auto">
              <option value="true"' . ( $this->get( 'blog' ) ? ' selected' : '' ) . '>Yes</option>
              <option value="false"' . ( $this->get( 'blog' ) ? '' : ' selected' ) . '>No</option>
            </select>
            <label for="footer" class="ss-label">Footer</label>
            <textarea rows="5" id="footer" name="footer" placeholder="' . sprintf( 'Copyright &copy; %d', date( 'Y' ) ) . '" class="ss-textarea ss-mobile ss-w-6 ss-mx-auto editable">' . $this->esc( $this->get( 'footer' ) ) . '</textarea>
            ' . $this->get_action( 'form' ) . '
            <input type="hidden" name="token" value="' . $this->token() . '">
            <input type="submit" name="save" value="Save changes" class="ss-btn ss-mobile ss-w-5">
          </form>
          <hr class="ss-hr">
          <form action="' . $this->admin_url( '?page=settings', true ) . '" method="post" class="ss-py-4">
            <h3 class="ss-monospace">Change Password</h3>
            <label for="old" class="ss-label">Current Password <span class="ss-red">*</span></label>
            <input type="password" id="old" name="old" class="ss-input ss-mobile ss-w-6 ss-mx-auto" required>
            <label for="new" class="ss-label">New Password <span class="ss-red">*</span></label>
            <input type="password" id="new" name="new" class="ss-input ss-mobile ss-w-6 ss-mx-auto" required>
            <label for="confirm" class="ss-label">Confirm Password <span class="ss-red">*</span></label>
            <input type="password" id="confirm" name="confirm" class="ss-input ss-mobile ss-w-6 ss-mx-auto" required>
            ' . $this->get_action( 'form' ) . '
            <input type="hidden" name="token" value="' . $this->token() . '">
            <input type="submit" name="password" value="Change password" class="ss-btn ss-mobile ss-w-5">
          </form>';
          if ( isset( $_POST[ 'save' ] ) ) {
            $this->auth();
            $this->get_action( 'on_settings' );
            $_POST[ 'email' ] = filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL );
            $_POST[ 'url' ] = rtrim( filter_input( INPUT_POST, 'url', FILTER_SANITIZE_URL ), './?&#' ) . '/';
            $_POST[ 'admin' ] = $this->esc_slug( $_POST[ 'admin' ] );
            $_POST[ 'blog' ] = filter_input( INPUT_POST, 'blog', FILTER_VALIDATE_BOOL );
            if ( file_exists( $this->root( $_POST[ 'admin' ] ) ) ) {
              $this->alert( 'Directory/File with the given custom admin url already exist.', 'error' );
              $this->go( $this->admin_url( '?page=settings' ) );
            }
            $data = $this->data();
            unset( $_POST[ 'token' ], $_POST[ 'save' ] );
            $data[ 'site' ] = array_merge( $this->data( 'site' ), $_POST );
            if ( $this->save( $data ) ) {
              $this->database = $data;
              $this->get_action( 'settings_success' );
              $this->alert( 'Settings updated successfully.', 'success' );
              $this->go( $this->admin_url( '?page=settings' ) );
            }
            $this->get_action( 'settings_error', $data );
            $this->alert( 'Failed to update settings, please try again.', 'error' );
            $this->go( $this->admin_url( '?page=settings' ) );
          } else if ( isset( $_POST[ 'password' ] ) ) {
            $this->auth();
            $this->get_action( 'on_password' );
            $old_pass = ( $_POST[ 'old' ] ?? '' );
            $new_pass = ( $_POST[ 'new' ] ?? '' );
            $confirm = ( $_POST[ 'confirm' ] ?? '' );
            if ( ! password_verify( $old_pass, $this->get( 'password' ) ) ) {
              $this->get_action( 'password_error' );
              $this->alert( 'Incorrect password, please try again.', 'error' );
              $this->go( $this->admin_url( '?page=settings' ) );
            } else if ( ! hash_equals( $new_pass, $confirm ) ) {
              $this->alert( 'Passwords does not match, please try again.', 'error' );
              $this->go( $this->admin_url( '?page=settings' ) );
            } else if ( 8 > strlen( $new_pass ) ) {
              $this->alert( 'Password must be at least 8 characters or longer.', 'error' );
              $this->go( $this->admin_url( '?page=settings' ) );
            } else {
              $hash = password_hash( $new_pass, PASSWORD_DEFAULT );
              if ( $this->set( $hash, 'password' ) ) {
                $this->get_action( 'password_success' );
                $this->alert( 'Password updated, please login again.', 'success' );
                $this->log( 'Password has been changed.' );
                unset( $_SESSION[ 'logged_in' ] );
              } else {
                $this->alert( 'Failed to change password, please try again.', 'error' );
              }
              $this->go( $this->admin_url() );
            }
          }
          break;
        default:
          $page = 'dashboard';
          $list = $this->data( 'pages' );
          $last = array_key_last( $list );
          $layout[ 'title' ] = 'Dashboard';
          $layout[ 'content' ] = '
          <p class="ss-h6">Welcome back, You are currently logged in as <b>admin</b>.</p>
          <ul class="ss-list ss-fieldset ss-mobile ss-w-6 ss-mx-auto">
            <li class="ss-bd-none">
              <h3 class="ss-monospace">Activities</h3>
              <hr class="ss-hr">
            </li>
            <li class="ss-responsive">
              <h4 class="ss-monospace">Recently Created</h4>
              <hr class="ss-hr ss-w-3 ss-mx-auto">
              <p class="ss-large"><a href="' . $this->url( $this->esc( $last ) ) . '" target="_blank" class="ss-dotted">' . $this->esc( $this->page( 'title', $last ) ) . '</a></p>
            </li>
            ' . $this->get_action( 'dashboard' ) . '
          </ul>';
          break;
      }
    }
    require_once $this->root( 'app/layout.php' );
  }
  
  /**
   * Render home, pages, and admin
   * @return void
   */
  public function render(): void {
    header( 'X-Powered-By: BoidCMS' );
    $this->get_action( 'render' );
    switch ( $this->page ) {
      case $this->admin_url():
        $this->admin();
        break;
      case $this->_( '', 'index' ):
        $this->get_action( 'home' );
        if ( $this->get( 'blog' ) ) {
          require_once $this->theme( 'blog.php' );
          break;
        }
        $this->page = 'home';
        $tpl = $this->theme( $this->page( 'tpl' ) );
        if ( is_file( $tpl ) ) {
          require_once $tpl;
          break;
        }
        require_once $this->theme( 'theme.php' );
        break;
      case $this->page( 'pub' ):
        $type = $this->page( 'type' );
        $this->get_action( $type . '_type' );
        $tpl = $this->theme( $this->page( 'tpl' ) );
        if ( is_file( $tpl ) ) {
          require_once $tpl;
          break;
        }
        require_once $this->theme( 'theme.php' );
        break;
      default:
        $page = $this->page;
        $this->page = '404';
        http_response_code(404);
        $this->get_action( '404', $page );
        $tpl = $this->theme( $this->page( 'tpl' ) );
        if ( is_file( $tpl ) ) {
          require_once $tpl;
        }
        require_once $this->theme( 'theme.php' );
        break;
    }
    $this->get_action( 'rendered' );
  }
}
?>
