<?php defined( 'App' ) or die( 'BoidCMS' ); global $App ?>
<!DOCTYPE html>
<html lang="<?= $App->esc( $App->get( 'lang' ) ) ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= $App->esc( $App->page( 'descr' ) ) ?>">
    <meta name="keywords" content="<?= $App->esc( $App->page( 'keywords' ) ) ?>">
    <meta name="generator" content="BoidCMS">
    <meta name="theme-color" content="#122">
    <title><?= $App->esc( $App->page( 'title' ) ) ?> &mdash; <?= $App->esc( $App->get( 'title' ) ) ?></title>
    <style>body,button,input,option,select,textarea{font:400 1.125rem/1.85625rem system-ui,"Helvetica Neue",Helvetica,Arial,sans-serif}dt,th{font-weight:600}td,th{border-bottom:0 solid #595959;padding:.928125rem 1.125rem;text-align:left;vertical-align:top}thead th{padding-bottom:.39375rem}table{display:table;width:100%}@media all and (max-width:1024px){table,table thead{display:none}}table tr,table tr th,thead th{border-bottom-width:.135rem}table tr td,table tr th{overflow:hidden;padding:.3375rem .225rem}@media all and (max-width:1024px){table tr td,table tr th{border:0;display:inline-block}table tr{margin:.675rem 0}table,table tr{display:inline-block}}cite,fieldset label,fieldset legend,figure img{display:block}fieldset legend{margin:1.125rem 0}input{padding:.61875rem}input,select,textarea{display:inline-block}button,input,select,textarea{color:#000!important;border-radius:3.6px}button+input[type=checkbox],button+input[type=radio],button+label,input+input[type=checkbox],input+input[type=radio],input+label,label+*,select+input[type=checkbox],select+input[type=radio],select+label,textarea+input[type=checkbox],textarea+input[type=radio],textarea+label{page-break-before:always}input,label,select{margin-right:.225rem}textarea{min-height:5.625rem;min-width:22.5rem}label{display:inline-block;margin-bottom:.7875rem}label>input{margin-bottom:0}input[type=reset],input[type=submit]{margin-bottom:1.125rem;text-align:center}input[type=submit]{background:#fff;cursor:pointer;display:inline;margin-right:.45rem;padding:.4078125rem 1.4625rem}input[type=reset]{color:#000}button,input[type=reset]{background:#fff;cursor:pointer;display:inline;margin-right:.45rem;padding:.4078125rem 1.4625rem}button:hover,input[type=reset]:hover{background:#ccc}button[disabled],input[type=button][disabled],input[type=reset][disabled],input[type=submit][disabled]{background:#ccc;color:#000!important;cursor:not-allowed}button[type=submit],input[type=submit]{background:#2a6f3b;color:#fff!important}button[type=submit]:hover,input[type=submit]:hover{background:#ccc;color:#000!important}button,h1,h2,h3,h4,h5,input,select,textarea{margin-bottom:1.125rem}input[type=color],input[type=date],input[type=datetime-local],input[type=datetime],input[type=email],input[type=file],input[type=month],input[type=number],input[type=password],input[type=phone],input[type=range],input[type=search],input[type=tel],input[type=text],input[type=time],input[type=url],input[type=week],select,textarea{border:1px solid #595959;padding:.3375rem .39375rem}input[type=checkbox],input[type=radio]{flex-grow:0;height:1.85625rem;margin-left:0;margin-right:9px;vertical-align:middle}input[type=checkbox]+label,input[type=radio]+label{page-break-before:avoid}select[multiple]{min-width:270px}code,kbd,output,pre,samp,var{font:.9rem Menlo,Monaco,Consolas,"Courier New",monospace}pre{border-left:0 solid #59c072;line-height:1.575rem;overflow:auto;padding-left:18px}pre code{background:0 0;border:0;line-height:1.85625rem;padding:0}code{line-height:1.125rem}code,kbd,nav ul li{display:inline-block}code,kbd{border-radius:3.6px;padding:.225rem .39375rem .16875rem;background:#2a6f3b}kbd{color:#fff}mark{background:#ffc;padding:0 .225rem}h5,h6{font-size:.9rem}h6,kbd{line-height:1.125rem}h6{margin-bottom:1.125rem}h1{font-size:2.25rem;line-height:2.7rem;margin-top:4.5rem}h2{font-size:1.575rem;line-height:2.1375rem;margin-top:3.375rem}h3{font-size:1.35rem;line-height:1.6875rem;margin-top:2.25rem}h4{font-size:1.125rem;line-height:1.4625rem;margin-top:1.125rem}h5{line-height:1.35rem}a,ins,u{text-decoration:underline}hr{border-bottom:1px solid #595959}figcaption,small{font-size:.95625rem}button,figcaption,nav,nav ul{text-align:center}em,i,var{font-style:italic}del,s{text-decoration:line-through}*,sub,sup{vertical-align:baseline}sub,sup{font-size:75%;line-height:0;position:relative}sup{top:-.5em}sub{bottom:-.25em}*{border:0;border-collapse:separate;border-spacing:0;box-sizing:border-box;margin:0;max-width:100%;padding:0;font-family:Consolas,"courier new",monospace!important}body,html{width:100%}html{height:100%}body{padding:36px}*,body,html{color:#fff!important}address,blockquote,dl,fieldset,figure,form,hr,ol,p,pre,table,ul{margin-bottom:1.85625rem}blockquote{border-left:5px solid #fff!important;font-family:Georgia,serif!important;padding:.28125rem 1.125rem .28125rem .99rem}blockquote:last-child{margin-bottom:0}cite:before{content:"— "}section{margin-left:auto;margin-right:auto;width:900px}aside{float:right;width:285px}article,footer,header{padding:43.2px}article{border:1px solid #d9d9d9}nav ul{list-style:none;margin-left:0}nav ul li{margin-left:9px;margin-right:9px;vertical-align:middle}nav ul li:first-child{margin-left:0}nav ul li:last-child{margin-right:0}ol,ul{margin-left:31.5px}blockquote p,li dl,li ol,li ul{margin-bottom:0}dl{display:inline-block}dt{padding:0 1.125rem}dd{padding:0 1.125rem .28125rem}dd:last-of-type{border-bottom:0 solid #595959}dd+dt{border-top:0 solid #595959;padding-top:.5625rem}blockquote footer{font-size:.84375rem;margin:0}img{height:auto;margin:0 auto}@media (max-width:767px){body{padding:18px 0}article{border:0}article,footer,header{padding:18px}fieldset,input,select,textarea{min-width:0}fieldset *{flex-grow:1;page-break-before:auto}section{width:auto}x:-moz-any-link{display:table-cell}}body,html{background-color:#122!important}body>section>h1{text-decoration:underline dotted}b,h1,h2,h3,h4,h5,h6,strong{font-weight:700}a:hover,body>footer>aside a{text-decoration:none;outline:2px dashed #fff!important}</style>
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
    <?php if ( 'post' === $App->page( 'type' ) ): ?>
      <?= $App->get_action( 'post_top' ) ?>
    <?php else: ?>
      <?= $App->get_action( 'page_top' ) ?>
    <?php endif ?>
    <section>
      <h1><?= $App->page( 'title' ) ?></h1>
      <?= $App->get_action( 'site_under_title' ) ?>
      <?php if ( 'post' === $App->page( 'type' ) ): ?>
      <p style="font-size:.7em">
        <i>Published On</i>: <kbd><time datetime="<?= $App->page( 'date' ) ?>"><?= date( 'F j, Y H:i:s', strtotime( $App->page( 'date' ) ) ) ?></time></kbd>
        <?php $keywords = trim( $App->page( 'keywords' ) ) ?>
        <?php $keywords = trim( $keywords, ',' ) ?>
        <?php if ( ! empty( trim( $keywords ) ) ): ?>
        <br><i>Keywords</i>: <kbd><?= str_replace( ',', '</kbd> <kbd>', $keywords ) ?></kbd>
        <?php endif ?>
      </p>
      <?php endif ?>
    </section>
    <?php if ( $App->page( 'thumb' ) ): ?>
    <figure>
      <img src="<?= $App->url( 'media/' . $App->page( 'thumb' ) ) ?>" alt="<?= $App->esc( $App->page( 'title' ) ) ?>">
    </figure>
    <?php endif ?>
    <article>
      <h1 style="display:none"><?= $App->page( 'title' ) ?></h1>
      <?php if ( 'post' === $App->page( 'type' ) ): ?>
        <?= $App->get_action( 'post_content_top' ) ?>
        <p><?= $App->page( 'content' ) ?></p>
        <?= $App->get_action( 'post_content_end' ) ?>
      <?php else: ?>
      <?= $App->page( 'content' ) ?>
      <?php endif ?>
    </article>
    <?php if ( 'post' === $App->page( 'type' ) ): ?>
      <?= $App->get_action( 'post_end' ) ?>
    <?php else: ?>
      <?= $App->get_action( 'page_end' ) ?>
    <?php endif ?>
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
