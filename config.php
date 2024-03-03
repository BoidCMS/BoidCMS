<?php defined( 'App' ) or die( 'BoidCMS' );
return array(
  'site' => array(
    'lang' => 'en',
    'title' => 'BoidCMS',
    'keywords' => 'BoidCMS, keywords, for, seo',
    'subtitle' => 'Simple, fast, super extensible.',
    'descr' => 'Flat file CMS for building simple websites and blogs.',
    'url' => 'http' . ( filter_var( $_SERVER[ 'HTTPS' ] ?? 0, FILTER_VALIDATE_BOOL ) || ( $_SERVER[ 'SERVER_PORT' ] == 443 ) ? 's' : '' ) . '://' . $_SERVER[ 'SERVER_NAME' ] . ( ( ( $_SERVER[ 'SERVER_PORT' ] == 80 ) || ( $_SERVER[ 'SERVER_PORT' ] == 443 ) ) ? '' : ':' . $_SERVER[ 'SERVER_PORT' ] ) . ( ( dirname( $_SERVER[ 'SCRIPT_NAME' ] ) === '/' ) ? '' : dirname( $_SERVER[ 'SCRIPT_NAME' ] ) ) . '/',
    'email' => 'mail@example.com',
    'username' => 'admin',
    'password' => password_hash( 'password', PASSWORD_DEFAULT ),
    'footer' => 'Copyright &copy; ' . date( 'Y' ),
    'theme' => 'nimble',
    'admin' => 'admin',
    'blog' => false
  ),
  'pages' => array(
    404 => array(
      'type' => 'page',
      'title' => 'Not Found',
      'descr' => '404 Page Not Found',
      'keywords' => '404',
      'content' => '<p style="text-align:center">It looks like nothing was found at this location.</p>',
      'thumb' => '',
      'date' => '',
      'tpl' => '',
      'pub' => false
    ),
    'home' => array(
      'type' => 'page',
      'title' => 'Home',
      'descr' => 'Set up your new site',
      'keywords' => 'BoidCMS, keywords, for, seo',
      'content' => '<h2>Edit This Page</h2><p>Visit the <a href="./admin">admin panel</a> and login if not already, then Navigate to <b>Update</b>, select <b>Home (home)</b> and click <b>Select</b>, Update the fields you want to change and click <b>Update</b> to save the changes.</p><h2>Enable Blog</h2><p>Navigate to <b>Settings</b>, scroll to <b>Enable Blog</b> and select <b>Yes</b>, then click <b>Save changes</b>.</p><h2>More Info</h2><p>You can find more information on how to set up your site in the <a href="https://boidcms.github.io" target="_blank" rel="nofollow">documentation</a>.</p>',
      'thumb' => '',
      'date' => '',
      'tpl' => '',
      'pub' => true
    ),
    'hello-world' => array(
      'type' => 'post',
      'title' => 'Welcome to BoidCMS!',
      'descr' => 'Welcome to your new site',
      'keywords' => 'BoidCMS, keywords, for, seo',
      'content' => '<h1>Welcome to BoidCMS!</h1><p>This is a sample post, login now to edit or delete it.</p><p>Have fun! :)</p>',
      'thumb' => '',
      'date' => date( 'Y-m-d\TH:i:s' ),
      'tpl' => '',
      'pub' => true
    )
  ),
  'installed' => array()
);
?>
