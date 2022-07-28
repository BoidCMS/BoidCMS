<?php include ( __DIR__ . '/head.php' ) ?>
  <?= $App->get_action( 'home_top' ) ?>
  <div class="ss-container ss-mb-7">
    <p class="ss-h2 ss-dotted ss-center"><?= $App->_( empty( vsl_posts() ) ? 'No Posts Available' : 'Latest Posts' ) ?></p>
  </div>
  <?php foreach ( vsl_posts() as $slug => $post ): ?>
    <div class="ss-container ss-my-6">
      <div class="ss-card-3 ss-hvr-card-5 ss-round-xxlarge">
        <?php if ( ! empty( $post[ 'thumb' ] ) ): ?>
        <img src="<?= $App->url( 'media/' . $post[ 'thumb' ] ) ?>" alt="<?= $post[ 'thumb' ] ?>" loading="lazy" width="100%" height="100%" class="ss-full">
        <?php endif ?>
        <div class="ss-container">
          <?= $App->get_action( 'post_list_top', $slug ) ?>
          <p><time datetime="<?= $post[ 'date' ] ?>" class="ss-dotted ss-large"><?= date( 'F j, Y', strtotime( $post[ 'date' ] ) ) ?></time></p>
          <h2 class="subtitle"><a href="<?= $App->url( $slug ) ?>" class="ss-dotted"><?= $App->esc( $post[ 'title' ] ) ?></a></h2>
          <p class="ss-xlarge"><?= substr( strip_tags( $post[ 'content' ] ), 0, 200 ) ?></p>
          <?= $App->get_action( 'post_list_end', $slug ) ?>
        </div>
      </div>
    </div>
  <?php endforeach ?>
  <?php if ( vsl_paginate() ): ?>
    <div class="ss-container ss-my-7">
      <p class="ss-h2 ss-center"><a href="<?= $App->url( '?page=' . vsl_page() + 1 ) ?>" rel="next" class="ss-hvr-dotted">Load More</a></p>
    </div>
  <?php endif ?>
  <?= $App->get_action( 'home_end' ) ?>
<?php include ( __DIR__ . '/footer.php' ) ?>
