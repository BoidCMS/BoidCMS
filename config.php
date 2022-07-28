<?php defined( 'App' ) or die( 'BoidCMS' );
return array(
  'site' => array(
    'lang' => 'en',
    'title' => 'BoidCMS',
    'keywords' => 'BoidCMS, Keywords, for, seo',
    'subtitle' => 'Simple, fast, super extensible.',
    'descr' => 'Simple, fast, super extensible CMS to build simple websites at ease.',
    'url' => 'http' . ( ( ! empty( $_SERVER[ 'HTTPS' ] ) && ( $_SERVER[ 'HTTPS' ] !== 'off' ) ) || ( ( int ) $_SERVER[ 'SERVER_PORT' ] === 443 ) ? 's' : '' ) . '://' . $_SERVER[ 'SERVER_NAME' ] . ( ( ( $_SERVER[ 'SERVER_PORT' ] == '80' ) || ( $_SERVER[ 'SERVER_PORT' ] == '443' ) ) ? '' : ':' . $_SERVER[ 'SERVER_PORT' ] ) . ( ( dirname( $_SERVER[ 'SCRIPT_NAME' ] ) === '/' ) ? '' : dirname( $_SERVER[ 'SCRIPT_NAME' ] ) ) . '/',
    'email' => 'mail@example.com',
    'username' => 'admin',
    'password' => password_hash( 'password', PASSWORD_DEFAULT ),
    'footer' => 'Copyright &copy; ' . date( 'Y' ),
    'theme' => 'visual',
    'admin' => 'admin'
  ),
  'pages' => array(
    404 => array(
      'type' => 'page',
      'title' => 'Not Found',
      'descr' => '404 Page Not Found',
      'keywords' => '404',
      'content' => '<p>It looks like nothing was found at this location.</p>',
      'thumb' => '',
      'date' => '',
      'pub' => true
    ),
    'hello-world' => array(
      'type' => 'post',
      'title' => 'Welcome to BoidCMS!',
      'descr' => 'Welcome to your new website',
      'keywords' => 'BoidCMS, Keywords, for, seo',
      'content' => '<h1>Welcome to BoidCMS!</h1><p>This is a sample post, login now to edit or delete it.</p><p>Have fun! :)</p>',
      'thumb' => '',
      'date' => date( 'Y-m-d\TH:i:s' ),
      'pub' => true
    )
  ),
  'installed' => array()
);
?>
