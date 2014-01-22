<?php get_template_part('templates/head'); ?>
<body <?php body_class(); ?> data-ng-app="MCCApp">

  <!--[if lt IE 8]>
    <div class="alert alert-warning">
      <?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'roots'); ?>
    </div>
  <![endif]-->

  <?php
    do_action('get_header');

    if (is_front_page()) {
        get_template_part('templates/header-top-navbar');
    } else {
        // Use Bootstrap's navbar if enabled in config.php
        if (current_theme_supports('bootstrap-top-navbar')) {
            get_template_part('templates/header-top-navbar');
            get_template_part('templates/header-title');
        } else {
            get_template_part('templates/header');
            get_template_part('templates/header-title');
        }
    }

  ?>


      <main class="main" role="main">
          <div class="container">
              <?php
                  if (is_front_page()) {
                      get_template_part('templates/header-title');
                  }

                  include roots_template_path();
              ?>
          </div>
      </main><!-- /.main -->


  <?php
      if (is_front_page()) {
          get_template_part('templates/footer', 'home');
      } else {
          get_template_part('templates/footer');
      }
  ?>


</body>
</html>
