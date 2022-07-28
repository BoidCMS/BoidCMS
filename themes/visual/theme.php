<?php include ( __DIR__ . '/head.php' ) ?>
  <?= $App->get_action( 'page_top' ) ?>
  <h1 class="subtitle ss-center ss-mb-7"><?= $App->esc( $App->page( 'title' ) ) ?></h1>
  <?= $App->get_action( 'site_under_title' ) ?>
  <?= $App->page( 'content' ) ?>
  <?= $App->get_action( 'page_end' ) ?>
<?php include ( __DIR__ . '/footer.php' ) ?>
