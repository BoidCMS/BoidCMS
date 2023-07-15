<?php defined( 'App' ) or die( 'BoidCMS' ); global $App ?>
<!DOCTYPE html>
<html lang="<?= $App->esc( $App->get( 'lang' ) ) ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= $App->esc( $App->get( 'descr' ) ) ?>">
    <meta name="keywords" content="<?= $App->esc( $App->get( 'keywords' ) ) ?>">
    <meta name="generator" content="BoidCMS">
    <meta name="theme-color" content="#122">
    <title><?= $App->esc( $App->get( 'title' ) ) ?></title>
    <style>body{font:400 1.125rem/1.85625rem Consolas,"courier new",monospace}kbd{font:.9rem Menlo,Monaco,Consolas,"Courier New",monospace;background:#daf1e0;border-radius:3.6px;display:inline-block;line-height:1.125rem;padding:.225rem .39375rem .16875rem;background:#2a6f3b;color:#fff}h1,h2{margin-bottom:1.125rem}h1{font-size:2.25rem;line-height:2.7rem;margin-top:4.5rem}h2{font-size:1.575rem;line-height:2.1375rem;margin-top:3.375rem}a,a:hover{text-decoration:none}i{font-style:italic}*{border:0;border-collapse:separate;border-spacing:0;box-sizing:border-box;margin:0;max-width:100%;padding:0;vertical-align:baseline;color:#fff!important;font-family:Consolas,"courier new",monospace!important}body,html{width:100%}html{height:100%}body{color:#fff;padding:36px}p,ul{margin-bottom:1.85625rem}section{margin-left:auto;margin-right:auto;width:900px}aside{float:right;width:285px}article,footer{padding:43.2px}article{background:#fff;border:1px solid #d9d9d9}nav,nav ul{text-align:center}nav ul{list-style:none;margin-left:0}ul{margin-left:31.5px}@media (max-width:767px){body{padding:18px 0}article{border:0}article,footer{padding:18px}section{width:auto}}body,body>article,html{background-color:#122!important}body>section>h1{text-decoration:underline dotted}body>article{color:#000!important}b,h1,h2,strong{font-weight:700}a:hover,body>footer>aside a{outline:2px dashed #fff!important}</style>
    <?= $App->get_action( 'favicon', '' ) ?>
    <?= $App->get_action( 'site_head' ) ?>
  </head>
  <body>
    <?= $App->get_action( 'site_top' ) ?>
    <nav>
      <ul>
        <?= $App->get_action( 'site_nav', '<li><a href="%s">%s</a></li>' ) ?>
      </ul>
    </nav>
    <?= $App->get_action( 'home_top' ) ?>
    <section>
      <h1><?= $App->get( 'title' ) ?></h1>
      <p><?= $App->get( 'subtitle' ) ?></p>
      <?= $App->get_action( 'site_under_subtitle' ) ?>
    </section>
    <?php $posts = array_reverse( $App->data()[ 'pages' ], true ) ?>
    <?php $posts = $App->get_filter( $posts, 'recent_posts' ) ?>
    <?php foreach ( $posts as $slug => $page ): ?>
      <?php if ( 'post' === $page[ 'type' ] ): ?>
        <?php if ( $page[ 'pub' ] ): ?>
        <article>
          <?= $App->get_action( 'post_list_top', $slug ) ?>
          <h2 style="font-style:oblique"><a href="<?= $App->url( $slug ) ?>"><?= $page[ 'title' ] ?></a></h2>
          <p style="font-size:.7em">
            <i>Published On</i>: <kbd><time datetime="<?= $page[ 'date' ] ?>"><?= date( 'F j, Y H:i:s', strtotime( $page[ 'date' ] ) ) ?></time></kbd>
            <?php $keywords = trim( $page[ 'keywords' ] ) ?>
            <?php $keywords = trim( $keywords, ',' ) ?>
            <?php if ( ! empty( trim( $keywords ) ) ): ?>
              <br><i>Keywords</i>: <kbd><?= str_replace( ',', '</kbd> <kbd>', $keywords ) ?></kbd>
            <?php endif ?>
          </p>
          <p><?= nl2br( strip_tags( $page[ 'descr' ], '<br><strong><b>' ) ) ?></p>
          <?= $App->get_action( 'post_list_end', $slug ) ?>
        </article>
        <?php endif ?>
      <?php endif ?>
    <?php endforeach ?>
    <?= $App->get_action( 'home_end' ) ?>
    <footer>
      <?= $App->get_action( 'site_footer' ) ?>
      <p><?= $App->get( 'footer' ) ?></p>
      <aside>
        <p>Proudly powered by <a href="https://boidcms.github.io" target="_blank">BoidCMS</a></p>
      </aside>
    </footer>
    <?= $App->get_action( 'site_end' ) ?>
  </body>
</html>
