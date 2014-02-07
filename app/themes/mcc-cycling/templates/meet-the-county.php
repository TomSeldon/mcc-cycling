<?php
    // Set the background image
    $mtc_bg_img = get_redux_field('bg_mtc');

    if (isset($mtc_bg_img['url'])) {
        $mtc_bg_img = $mtc_bg_img['url'];
    } else {
        $mtc_bg_img = '';
    }

    // Get vars
    $mtc_show_cta           = get_field('mtc_show_call_to_action');
    $mtc_cta_button_text    = '';
    $mtc_cta_href           = '';

    if ($mtc_show_cta) {
        $mtc_cta_button_text    = get_field('mtc_cta_button_text');
        $mtc_cta_link_type      = get_field('mtc_cta_link_type');
        $mtc_cta_href           = null;

        switch ($mtc_cta_link_type) {
            case 'page':
                $mtc_cta_href  = get_field('mtc_cta_page');
                break;

            case 'url':
                $mtc_cta_href  = get_field('mtc_cta_url');
                break;
        }
    }
?>

<div class="container section" data-stellar-background-ratio="0.8" id="meet-the-county" style="background-image: url('<?php echo $mtc_bg_img; ?>');">
    <div class="row">
        <div class="col-md-5">
            <h1><?php the_field('mtc_heading'); ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 content">
            <?php the_field('mtc_content'); ?>
        </div>
    </div>
    <?php if ($mtc_show_cta): ?>
        <div class="row cta">
            <div class="col-md-5">
            <span>
                <a href="<?php echo $mtc_cta_href; ?>" class="redButton"><?php echo $mtc_cta_button_text; ?></a>
            </span>
            </div>
        </div>
    <?php endif; ?>
</div>
