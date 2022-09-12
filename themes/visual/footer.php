<?php defined( 'App' ) or die( 'BoidCMS' ); global $App ?>
      </div>
    </div>
    <div class="ss-bottom ss-container ss-py-5 footer">
      <?= $App->get_action( 'site_footer' ) ?>
      <p class="ss-xlarge ss-ml-3 ss-left-align"><?= $App->_( $App->get( 'footer' ) ) ?></p>
    </div>
    <?= $App->get_action( 'site_end' ) ?>
  </body>
</html>
