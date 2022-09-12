<?php defined( 'App' ) or die( 'BoidCMS' );
/**
 *
 * Visual - default theme
 *
 * @package BoidCMS
 * @subpackage Visual
 * @author Shoaiyb Sysa
 * @version 2.0.0
 */

global $App;
$App->set_action( 'render', 'vsl_init' );
$App->set_action( 'change_theme', 'vsl_shut' );
$App->set_action( 'editable_pages', 'vsl_editable' );
$App->set_action( 'admin', 'vsl_admin' );
$App->set_action( 'tpl', 'vsl_tpl' );

/**
 * Initiate Visual, first time install
 * @return void
 */
function vsl_init(): void {
  global $App;
  if ( 'visual' === $App->get( 'theme' ) ) {
    if ( ! $App->get( 'vsl' ) ) {
      $vsl = array();
      $vsl[ 'per' ] = 5;
      $vsl[ 'menu' ] = array(
        array(
          'text' => 'Home',
          'link' => $App->url()
        ),
        array(
          'text' => 'About',
          'link' => $App->url( 'about' )
        ),
        array(
          'text' => 'Contact',
          'link' => $App->url( 'contact' )
        )
      );
      $vsl[ 'top' ] = '<em class="ss-dotted">' . $App->get( 'title' ) . '</em>: ' . $App->get( 'subtitle' );
      $App->set( $vsl, 'vsl' );
    }
  }
}

/**
 * Free database space, while uninstalled
 * @param string $theme
 * @return void
 */
function vsl_shut( string $theme ): void {
  global $App;
  if ( 'visual' !== $theme ) {
    $App->unset( 'vsl' );
  }
}

/**
 * Custom templates
 * @return string
 */
function vsl_tpl(): string {
  return ',post.php,';
}

/**
 * Editable for wysiwyg editors
 * @return string
 */
function vsl_editable(): string {
  return ',visual,';
}

/**
 * Check if is homepage
 * @return bool
 */
function vsl_is_home(): bool {
  global $App;
  if ( ! $App->get( 'blog' ) ) {
    return ( $App->page === 'home' );
  }
  return ( $App->page === $App->_( '', 'index' ) );
}

/**
 * Site and page title
 * @return string
 */
function vsl_title(): string {
  global $App;
  $site = $App->esc( $App->get( 'title' ) );
  $page = $App->esc( $App->page( 'title' ) );
  if ( empty( $page ) ) {
    return $site;
  }
  return ( $page . ' &ndash; ' . $site );
}

/**
 * Site or page data
 * @return string
 */
function vsl_site_page( string $index ): string {
  global $App;
  $site = $App->get( $index );
  $page = $App->page( $index );
  if ( vsl_is_home() ) {
    return $App->esc( $site );
  }
  return $App->esc( $page );
}

/**
 * Published posts
 * @param ?int &$count
 * @return array
 */
function vsl_posts( ?int &$count = null ): array {
  global $App;
  $posts = array();
  $pages = $App->data()[ 'pages' ];
  $pages = array_reverse( $pages );
  foreach ( $pages as $slug => $p ) {
    if ( $p[ 'type' ] === 'post' && $p[ 'pub' ] ) {
      $posts[ $slug ] = $p;
    }
  }
  $page = vsl_page();
  $per = $App->get( 'vsl' )[ 'per' ];
  $posts = array_chunk( $posts, $per, true );
  $count = count( $posts );
  if ( isset( $posts[ $page ] ) ) {
    return $posts[ $page ];
  }
  return ( $posts[0] ?? array() );
}

/**
 * Pagination page
 * @return int
 */
function vsl_page(): int {
  return intval( $_GET[ 'page' ] ?? 0 );
}

/**
 * Pagination bar
 * @return bool
 */
function vsl_paginate(): bool {
  global $App;
  $per = $App->get( 'vsl' )[ 'per' ];
  $page = ( ( vsl_page() + 1 )  * $per );
  $posts = vsl_posts( $count );
  if ( $count <= $page ) {
    return false;
  }
  return ( $count > 1 );
}

/**
 * Admin settings
 * @return void
 */
function vsl_admin(): void {
  global $App, $layout, $page;
  switch ( $page ) {
    case 'visual':
      $menu = $App->get( 'vsl' )[ 'menu' ];
      $layout[ 'title' ] = 'Visual';
      $layout[ 'content' ] = '
      <form action="' . $App->admin_url( '?page=visual', true ) . '" method="post">
        <fieldset class="ss-fieldset ss-mobile ss-w-6 ss-mx-auto">
          <legend class="ss-legend">Menu</legend>
          <p class="ss-alert ss-info">Leave the text input empty to disable the link from showing up in menu.</p>
          <label for="menu_1_text" class="ss-label">Menu 1 Text</label>
          <input type="text" id="menu_1_text" name="menu[0][text]" value="' . $App->esc( $menu[0][ 'text' ] ) . '" class="ss-input ss-mobile ss-w-6 ss-mx-auto">
          <label for="menu_1_link" class="ss-label">Menu 1 Link</label>
          <input type="text" id="menu_1_link" name="menu[0][link]" value="' . $App->esc( $menu[0][ 'link' ] ) . '" class="ss-input ss-mobile ss-w-6 ss-mx-auto">
          <hr class="ss-hr">
          <label for="menu_2_text" class="ss-label">Menu 2 Text</label>
          <input type="text" id="menu_2_text" name="menu[1][text]" value="' . $App->esc( $menu[1][ 'text' ] ) . '" class="ss-input ss-mobile ss-w-6 ss-mx-auto">
          <label for="menu_2_link" class="ss-label">Menu 2 Link</label>
          <input type="text" id="menu_2_link" name="menu[1][link]" value="' . $App->esc( $menu[1][ 'link' ] ) . '" class="ss-input ss-mobile ss-w-6 ss-mx-auto">
          <hr class="ss-hr">
          <label for="menu_3_text" class="ss-label">Menu 3 Text</label>
          <input type="text" id="menu_3_text" name="menu[2][text]" value="' . $App->esc( $menu[2][ 'text' ] ) . '" class="ss-input ss-mobile ss-w-6 ss-mx-auto">
          <label for="menu_3_link" class="ss-label">Menu 3 Link</label>
          <input type="text" id="menu_3_link" name="menu[2][link]" value="' . $App->esc( $menu[2][ 'link' ] ) . '" class="ss-input ss-mobile ss-w-6 ss-mx-auto">
        </fieldset>
        <label for="per" class="ss-label">Posts Per Page</label>
        <input type="number" id="per" name="per" value="' . $App->get( 'vsl' )[ 'per' ] . '" min="1" class="ss-input ss-mobile ss-w-6 ss-mx-auto" required>
        <label for="content" class="ss-label">Top Header Text</label>
        <textarea rows="10" id="content" name="top" class="ss-textarea ss-mobile ss-w-6 ss-mx-auto" required>' . $App->get( 'vsl' )[ 'top' ] . '</textarea>
        <input type="hidden" name="token" value="' . $App->token() . '">
        <input type="submit" name="save" value="Save" class="ss-btn ss-mobile ss-w-5">
      </form>';
      if ( isset( $_POST[ 'save' ] ) ) {
        $App->auth();
        $vsl = array();
        $vsl[ 'per' ] = ( $_POST[ 'per' ] ?? '' );
        $vsl[ 'top' ] = ( $_POST[ 'top' ] ?? '' );
        $vsl[ 'menu' ] = ( $_POST[ 'menu' ] ?? array() );
        if ( $App->set( $vsl, 'vsl' ) ) {
          $App->alert( 'Settings saved successfully.', 'success' );
          $App->go( $App->admin_url( '?page=visual' ) );
        }
        $App->alert( 'Failed to save settings, please try again.', 'error' );
        $App->go( $App->admin_url( '?page=visual' ) );
        break;
      }
      require_once $App->root( 'app/layout.php' );
      break;
  }
}
?>
