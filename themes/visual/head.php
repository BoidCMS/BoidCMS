<?php defined( 'App' ) or die( 'BoidCMS' ); global $App ?>
<!DOCTYPE html>
<html lang="<?= $App->get( 'lang' ) ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= vsl_site_page( 'descr' ) ?>">
    <meta name="keywords" content="<?= vsl_site_page( 'keywords' ) ?>">
    <meta name="theme-color" content="#222222">
    <meta name="generator" content="BoidCMS">
    <title><?= vsl_title() ?></title>
    <link rel="canonical" href="<?= $App->url( $App->page ) ?>">
    <?php if ( $App->page === $App->_( '', 'index' ) ): ?>
      <?php if ( vsl_paginate() ): ?>
      <link rel="next" href="<?= $App->url( '?page=' . vsl_page() + 1 ) ?>">
      <?php endif ?>
      <?php if ( 0 !== vsl_page() && vsl_paginate() ): ?>
      <link rel="prev" href="<?= $App->url( '?page=' . vsl_page() - 1 ) ?>">
      <?php endif ?>
    <?php endif ?>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Didact+Gothic&display=swap">
    <link rel="stylesheet" href="<?= $App->theme( 'style.css', false ) ?>">
    <?= $App->_( '<link rel="icon" type="image/svg+xml" href="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA2NDAgNjQwIj48cGF0aCBmaWxsPSIjMDBiY2Q0IiBkPSJNMzIwIDExYTMwOSAzMDkgMCAxIDEgMCA2MTggMzA5IDMwOSAwIDAgMSAwLTYxOHoiLz48cGF0aCBmaWxsPSIjZmZmIiBkPSJNNDcxIDM1MHYtMjJoLTQ1di0yM2gyM3YtMjNoMjJ2LTkxaC0yMnYtMjJoLTIzdi0yM0gxNDZ2NjhoMjN2MjIwaC0yM3Y2MGgzMDN2LTIzaDIydi0yMmgyM3YtOTloLTIzem0tNzUgNjFoLTIzdjIzSDI0NHYtNjloMTI5djIzaDIzdjIzem0wLTE1OWgtMjN2MjNIMjQ0di02OWgxMjl2MjNoMjN2MjN6Ii8+PC9zdmc+Cg==">', 'favicon' ) ?>
    <?= $App->get_action( 'site_head' ) ?>
  </head>
  <body>
    <?= $App->get_action( 'site_top' ) ?>
    <div class="ss-top ss-bg-light-black ss-white ss-py-5">
      <div class="ss-container ss-center ss-large ss-my-5">
        <?php foreach ( $App->get( 'vsl' )[ 'menu' ] as $menu ): ?>
          <?php if ( ! empty( $menu[ 'text' ] ) ): ?>
          <a href="<?= $menu[ 'link' ] ?>" class="ss-button ss-card-2 ss-bg-transparent"><?= $menu[ 'text' ] ?></a>
          <?php endif ?>
        <?php endforeach ?>
      </div>
      <?php if ( $App->page === $App->_( '', 'index' ) ): ?>
      <div class="ss-container ss-my-7 ss-mx-auto">
        <h2 class="subtitle ss-wide ss-mobile ss-w-8 ss-mx-auto"><?= $App->get( 'vsl' )[ 'top' ] ?></h2>
      </div>
      <?php $App->get_action( 'site_under_subtitle' ) ?>
      <?php endif ?>
    </div>
    <div class="ss-container ss-mobile ss-w-8 ss-my-5 ss-mx-auto ss-py-7">
      <div class="ss-container ss-large">
