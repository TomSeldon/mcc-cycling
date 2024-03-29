<?php
    // Get vars
    $show_cta           = get_field('show_call_to_action');
    $show_cta           = apply_filters('mcc_header_show_call_to_action', $show_cta);
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

        $cta_button_text = apply_filters('mcc_header_cta_text', $cta_button_text);
        $cta_href        = apply_filters('mcc_header_cta_href', $cta_href);
    }

    // Set the background image
    $header_bg_img = get_redux_field('bg_header');

    if (isset($header_bg_img['url'])) {
        $header_bg_img = $header_bg_img['url'];
    } else {
        $header_bg_img = '';
    }

    $header_stellar_vert_offset = 150;

    if (is_front_page())
        $header_stellar_vert_offset = 0;

?>

<header id="top-header"
        data-stellar-background-ratio="0.5"
        data-stellar-vertical-offset="<?php echo $header_stellar_vert_offset; ?>"
        class="section parallax-bg"
        style="background-image: url('<?php echo $header_bg_img; ?>');">
    <div class="container">
        <nav class="row top-menu animated">
            <div class="col-xs-5 pull-left nav-toggle item">
                <button type="button" class="btn btn-default" data-toggle="collapse" data-target=".navbar-side-collapse">
                    <i class="glyphicon glyphicon-align-justify"></i>
                    <span>&nbsp;Menu</span>
                </button>
            </div>
            <div class="col-xs-5 pull-right login item">
                <a
                    class="btn btn-default"
                    href="<?php the_redux_field('login_url'); ?>">
                    <span>Sign up&nbsp;</span>
                    <i class="glyphicon glyphicon-lock"></i>
                </a>
            </div>
        </nav>

        <?php get_template_part('templates/logos'); ?>
        <?php get_template_part('templates/page', 'header') ?>

        <?php if ($show_cta): ?>
            <div class="row cta animated fadeInDown">
                <a href="<?php echo $cta_href; ?>" class="redButton">
                    <?php echo $cta_button_text; ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</header>