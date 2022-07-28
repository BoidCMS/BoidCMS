<?php include ( __DIR__ . '/head.php' ) ?>
  <?= $App->get_action( 'post_top' ) ?>
  <?php if ( ! empty( $App->page( 'thumb' ) ) ): ?>
  <img src="<?= $App->url( 'media/' . $App->page( 'thumb' ) ) ?>" alt="<?= $App->page( 'thumb' ) ?>" loading="lazy" class="ss-card-5 ss-full ss-round-xxlarge ss-mb-7">
  <?php endif ?>
  <h1 class="subtitle ss-center ss-mb-7"><?= $App->esc( $App->page( 'title' ) ) ?></h1>
  <?= $App->get_action( 'site_under_title' ) ?>
  <?= $App->get_action( 'post_content_top' ) ?>
  <?= $App->page( 'content' ) ?>
  <?= $App->get_action( 'post_content_end' ) ?>
  <?= $App->get_action( 'post_end' ) ?>
<?php include ( __DIR__ . '/footer.php' ) ?>
