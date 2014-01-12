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

<header id="top-header" style="background-image: url('<?php echo $header_bg_img; ?>');">
    <div class="container">
        <div class="row logos">
            <div class="col-sm-offset-2 col-sm-3" role="navigation">
                <a href="http://www.monmouthshire.gov.uk/">
                    <img src="/app/uploads/monmouthshire-cc-logo.png" width="165" height="37" />
                </a>
            </div>
            <div class="col-sm-3" role="navigation">
                <a href="http://monleisure.co.uk/">
                    <img src="/app/uploads/monmouthshire-leisure-logo.png" width="140" height="42" />
                </a>
            </div>
            <div class="col-sm-3" role="navigation">
                <a href="http://www.britishcycling.org.uk/nationalroadchampionships">
                    <img src="/app/uploads/national-champ-logo.png" width="160" height="39" />
                </a>
            </div>
        </div>

        <div class="row headings">
            <h1><?php the_title(); ?></h1>
            <h2><?php the_field('sub_title'); ?></h2>
        </div>

        <?php if ($show_cta): ?>
        <div class="row cta">
            <a href="<?php echo $cta_href; ?>" class="redButton">
                <?php echo $cta_button_text; ?>
            </a>
        </div>
        <?php endif; ?>
    </div>
</header>