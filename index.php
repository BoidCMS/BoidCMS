<?php
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
 * @version 2.1.0
 * @licence MIT
 */
session_start();
define( 'App', true );
require ( __DIR__ . '/app/app.php' );
$App = new App( __DIR__ );
$App->render();
?>
