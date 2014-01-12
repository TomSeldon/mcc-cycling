<?php
    // Set the background image
    $partners_bg_img = get_redux_field('bg_partners');

    if (isset($partners_bg_img['url'])) {
        $partners_bg_img = $partners_bg_img['url'];
    } else {
        $partners_bg_img = '';
    }

    // Get vars
    $partners_show_cta           = get_field('partners_show_call_to_action');
    $partners_cta_button_text    = '';
    $partners_cta_href           = '';

    if ($partners_show_cta) {
        $partners_cta_button_text    = get_field('partners_cta_button_text');
        $partners_cta_link_type      = get_field('partners_cta_link_type');
        $partners_cta_href           = null;

        switch ($partners_cta_link_type) {
            case 'page':
                $partners_cta_href  = get_field('partners_cta_page');
                break;

            case 'url':
                $partners_cta_href  = get_field('partners_cta_url');
                break;
        }
    }
?>

<div class="container" id="partners" style="background-image: url('<?php echo $partners_bg_img; ?>');">
    <div class="row">
        <div class="col-lg-12">
            <h1><?php the_field('partners_heading'); ?></h1>
        </div>
    </div>
    <div class="row partners">
        <?php
            while (has_sub_field('partners')):
                $logo = get_sub_field('logo');
        ?>
            <div class="col-md-4 partner">
                <a href="<?php the_sub_field('url'); ?>">
                    <img src="<?php echo $logo['url']; ?>"
                         width="<?php echo $logo['width']; ?>"
                         height="<?php echo $logo['height']; ?>"
                         alt="<?php echo $logo['alt']; ?>"
                         class="logo" />
                </a>
            </div>
        <?php endwhile; ?>
    </div>
    <div class="row">
        <div class="col-lg-12 content">
            <?php the_field('partners_content'); ?>
        </div>
    </div>
    <?php if ($partners_show_cta): ?>
        <div class="row cta">
            <div class="col-lg-12">
            <span>
                <a href="<?php echo $partners_cta_href; ?>" class="redButton"><?php echo $partners_cta_button_text; ?></a>
            </span>
            </div>
        </div>
    <?php endif; ?>
</div>
