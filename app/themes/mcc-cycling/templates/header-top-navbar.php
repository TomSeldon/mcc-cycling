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

    $subtitle = get_field('sub_title');

    // Set the background image
    $header_bg_img = get_redux_field('bg_header');

    if (isset($header_bg_img['url'])) {
        $header_bg_img = $header_bg_img['url'];
    } else {
        $header_bg_img = '';
    }

?>

<!-- header -->
<div class="collapse navbar-side-collapse" role="navigation">
    <?php dynamic_sidebar('primary'); ?>

    <div class="row social">
        <div class="col-xs-1">
            <span class="sr-only">Facebook</span>
            <span class="icon-facebook"></span>
        </div>
        <div class="col-xs-1">
            <span class="sr-only">Twitter</span>
            <span class=""></span>
        </div>
        <div class="col-xs-1">
            <span class="sr-only">Google Plus</span>
            <span class=""></span>
        </div>
    </div>
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

            <?php if ($subtitle !== false): ?>
            <h2><?php echo $subtitle; ?></h2>
            <?php endif; ?>
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