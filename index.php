<?php
# PHP built-in server protection: block direct access to sensitive directories
# (.htaccess rules are ignored by php -S and when AllowOverride is disabled)
$uri = $_SERVER['REQUEST_URI'] ?? '';
$path = parse_url( $uri, PHP_URL_PATH );
if ( preg_match( '#^/(\.git|app|data)/#', $path ) ) {
    http_response_code( 404 );
    exit( 'Not Found' );
}

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
 * @version 2.1.5
 * @licence MIT
 */
session_start();
define( 'App', true );
require ( __DIR__ . '/app/app.php' );
$App = new App( __DIR__ );
$App->render();
