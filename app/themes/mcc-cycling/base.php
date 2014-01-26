<?php get_template_part('templates/head'); ?>
<body <?php body_class(); ?> data-ng-app="MCCApp">

  <!--[if lt IE 8]>
    <div class="alert alert-warning">
      <?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'roots'); ?>
    </div>
  <![endif]-->

  <?php
    do_action('get_header');

    // Use Bootstrap's navbar if enabled in config.php
    if (current_theme_supports('bootstrap-top-navbar')) {
        get_template_part('templates/header-top-navbar');
    } else {
        get_template_part('templates/header');
    }

    get_template_part('templates/header','back-to-top');

  ?>

  <div class="container" id="main-container">

      <?php get_template_part('templates/header-title'); ?>

      <?php if (roots_display_sidebar()) : ?>
      <div class="container">
      <?php endif; ?>

      <main class="main container <?php echo (roots_display_sidebar() ? 'col-sm-8' : ''); ?>" role="main">
          <?php
              include roots_template_path();
          ?>
      </main><!-- /.main -->

      <?php if (roots_display_sidebar()) : ?>
          <aside class="sidebar <?php echo roots_sidebar_class(); ?>" role="complementary">
              <?php include roots_sidebar_path(); ?>
          </aside><!-- /.sidebar -->
      </div>
      <?php endif; ?>

  </div>

  <?php
      if (is_front_page()) {
          get_template_part('templates/footer', 'home');
      } else {
          get_template_part('templates/footer');
      }
  ?>


</body>
</html>
