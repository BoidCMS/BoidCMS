<?php
/**
 *
 * Simple, fast, super extensible
 *
 * Fork of WonderCMS
 * @link https://www.wondercms.com
 *
 * @package BoidCMS
 * @author Shoaiyb Sysa
 * @link https://boidcms.github.io
 * @version 1.0.1
 * @licence MIT
 */
session_start();
define( 'App', true );
require ( __DIR__ . '/app/app.php' );
$config = require ( __DIR__ . '/config.php' );
$App = new App( $config, __DIR__ );
$App->render();
?>
