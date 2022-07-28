<?php defined( 'App' ) or die( 'BoidCMS' ); global $App, $layout, $page ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="generator" content="BoidCMS">
    <meta name="theme-color" content="#00bcd4">
    <title><?= $layout[ 'title' ] ?> – BoidCMS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/shoaiyb/sysacss/sysa.min.css">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA2NDAgNjQwIj48cGF0aCBmaWxsPSIjMDBiY2Q0IiBkPSJNMzIwIDExYTMwOSAzMDkgMCAxIDEgMCA2MTggMzA5IDMwOSAwIDAgMSAwLTYxOHoiLz48cGF0aCBmaWxsPSIjZmZmIiBkPSJNNDcxIDM1MHYtMjJoLTQ1di0yM2gyM3YtMjNoMjJ2LTkxaC0yMnYtMjJoLTIzdi0yM0gxNDZ2NjhoMjN2MjIwaC0yM3Y2MGgzMDN2LTIzaDIydi0yMmgyM3YtOTloLTIzem0tNzUgNjFoLTIzdjIzSDI0NHYtNjloMTI5djIzaDIzdjIzem0wLTE1OWgtMjN2MjNIMjQ0di02OWgxMjl2MjNoMjN2MjN6Ii8+PC9zdmc+Cg==">
    <?= $App->get_action( 'admin_head' ) ?>
  </head>
  <body class="ss-monospace ss-center ss-white">
    <?= $App->get_action( 'admin_top' ) ?>
    <?php if ( $App->logged_in ): ?>
      <div class="ss-top ss-responsive ss-bg-cyan ss-py-5">
        <div class="ss-btn-group ss-nowrap ss-container">
          <a href="<?= $App->url() ?>" target="_blank" class="ss-btn ss-inverted ss-bd-none ss-white">Preview</a>
          <a href="<?= $App->admin_url( abs: true ) ?>" class="ss-btn ss-inverted ss-bd-none ss-white<?= ( ( $page === 'dashboard' ) ? ' ss-dotted' : '' ) ?>">Dashboard</a>
          <a href="<?= $App->admin_url( '?page=settings', true ) ?>" class="ss-btn ss-inverted ss-bd-none ss-white<?= ( $page === 'settings' ? ' ss-dotted' : '' ) ?>">Settings</a>
          <a href="<?= $App->admin_url( '?page=create', true ) ?>" class="ss-btn ss-inverted ss-bd-none ss-white<?= ( $page === 'create' ? ' ss-dotted' : '' ) ?>">Create</a>
          <a href="<?= $App->admin_url( '?page=update', true ) ?>" class="ss-btn ss-inverted ss-bd-none ss-white<?= ( $page === 'update' ? ' ss-dotted' : '' ) ?>">Update</a>
          <a href="<?= $App->admin_url( '?page=delete', true ) ?>" class="ss-btn ss-inverted ss-bd-none ss-white<?= ( $page === 'delete' ? ' ss-dotted' : '' ) ?>">Delete</a>
          <a href="<?= $App->admin_url( '?page=media', true ) ?>" class="ss-btn ss-inverted ss-bd-none ss-white<?= ( $page === 'media' ? ' ss-dotted' : '' ) ?>">Media</a>
          <a href="<?= $App->admin_url( '?page=plugins', true ) ?>" class="ss-btn ss-inverted ss-bd-none ss-white<?= ( $page === 'plugins' ? ' ss-dotted' : '' ) ?>">Plugins</a>
          <?= $App->get_action( 'admin_nav' ) ?>
          <a href="<?= $App->admin_url( '?page=logout&token=' . $App->token(), true ) ?>" class="ss-btn ss-inverted ss-bd-none ss-white<?= ( $page === 'logout' ? ' ss-dotted' : '' ) ?>">Logout</a>
        </div>
      </div>
    <?php endif ?>
    <div class="ss-container ss-black ss-py-7">
      <?php $App->alerts() ?>
      <?= $App->get_action( 'admin_middle' ) ?>
      <?= $App->get_action( $page . '_top' ) ?>
      <?php if ( $App->logged_in ): ?>
        <h1 class="ss-monospace"><?= $layout[ 'title' ] ?></h1>
      <?php endif ?>
      <div class="ss-container">
        <?= $layout[ 'content' ] ?>
      </div>
      <?= $App->get_action( $page . '_end' ) ?>
    </div>
    <?php if ( $App->logged_in ): ?>
      <div class="ss-bottom ss-bg-cyan ss-py-7">
        <p class="ss-large">Powered by <a href="https://boidcms.github.io/" rel="nofollow" target="_blank" class="ss-dotted">BoidCMS</a> <sub>v<?= $App->version ?></sub></p>
        <?= $App->get_action( 'admin_footer' ) ?>
      </div>
    <?php endif ?>
    <?= $App->get_action( 'admin_end' ) ?>
  </body>
</html>
