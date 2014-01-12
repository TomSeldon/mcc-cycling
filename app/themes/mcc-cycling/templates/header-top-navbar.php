<?php
    // Get vars
    $show_cta           = get_field('show_call_to_action');
    $cta_button_text    = '';
    $cta_href           = '';

    if ($show_cta) {
        $cta_button_text    = get_field('cta_button_text');
        $cta_link_type      = get_field('cta_link_type');
        $cta_href           = null;

        switch ($cta_link_type) {
            case 'page':
                $cta_href  = get_field('cta_page');
                break;

            case 'url':
                $cta_href  = get_field('cta_url');
                break;
        }
    }

    // Set the background image
    $header_bg_img = get_redux_field('bg_header');

    if (isset($header_bg_img['url'])) {
        $header_bg_img = $header_bg_img['url'];
    } else {
        $header_bg_img = '';
    }

    // Header CTA
    if (true === $show_cta) {

    }

?>

<!--
<header class="banner navbar navbar-default navbar-static-top" role="banner">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo home_url(); ?>/"><?php bloginfo('name'); ?></a>
    </div>

    <nav class="collapse navbar-collapse" role="navigation">
      <?php
        if (has_nav_menu('primary_navigation')) :
          wp_nav_menu(array('theme_location' => 'primary_navigation', 'menu_class' => 'nav navbar-nav'));
        endif;
      ?>
    </nav>
  </div>
</header>
-->

<!-- header -->
<div class="collapse navbar-side-collapse" role="navigation">
    <?php dynamic_sidebar('primary'); ?>
    <?php
        if (has_nav_menu('primary_navigation')) :
           // wp_nav_menu(array('theme_location' => 'primary_navigation', 'menu_class' => 'nav navbar-nav'));
        endif;
    ?>
</div>

<header id="top-header" style="background-image: url('<?php echo $header_bg_img; ?>');">
    <div class="container">
        <nav class="row top-menu">
            <div class="col-xs-3 pull-left nav-toggle item">
                <button type="button" class="btn btn-default" data-toggle="collapse" data-target=".navbar-side-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <i class="glyphicon glyphicon-align-justify"></i>
                </button>
            </div>
            <div class="col-xs-3 pull-right login item">
                <a class="btn btn-default" href="<?php the_redux_field('login_url'); ?>">
                    <span class="sr-only">Login</span>
                    <i class="glyphicon glyphicon-lock"></i>
                </a>
            </div>
        </nav>

        <?php get_template_part('templates/logos'); ?>

        <div class="row headings animated fadeInDown">
            <h1><?php the_title(); ?></h1>
            <h2><?php the_field('sub_title'); ?></h2>
        </div>

        <?php if ($show_cta): ?>
        <div class="row cta animated fadeInDown">
            <a href="<?php echo $cta_href; ?>" class="redButton">
                <?php echo $cta_button_text; ?>
            </a>
        </div>
        <?php endif; ?>
    </div>
</header>