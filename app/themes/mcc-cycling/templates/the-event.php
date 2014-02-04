<?php
    // Set the background image
    $event_bg_img = get_redux_field('bg_event');

    if (isset($event_bg_img['url'])) {
        $event_bg_img = $event_bg_img['url'];
    } else {
        $event_bg_img = '';
    }

    // Get vars
    $event_show_cta           = get_field('event_show_call_to_action');
    $event_cta_button_text    = '';
    $event_cta_href           = '';

    if ($event_show_cta) {
        $event_cta_button_text    = get_field('event_cta_button_text');
        $event_cta_link_type      = get_field('event_cta_link_type');
        $event_cta_href           = null;

        switch ($event_cta_link_type) {
            case 'page':
                $event_cta_href  = get_field('event_cta_page');
                break;

            case 'url':
                $event_cta_href  = get_field('event_cta_url');
                break;
        }
    }
?>

<div class="container section" id="the-event" style="background-image: url('<?php echo $event_bg_img; ?>');">
    <div class="row">
        <div class="col-xs-12 col-md-5">
            <h1><?php the_field('event_heading'); ?></h1>
            <?php if ($event_show_cta): ?>
            <div class="row cta hidden-xs hidden-sm">
                <span>
                    <a href="<?php echo $event_cta_href; ?>" class="redButton"><?php echo $event_cta_button_text; ?></a>
                </span>
            </div>
            <?php endif; ?>
        </div>
        <div class="col-xs-12 col-md-7 content">
            <?php the_field('event_content'); ?>
        </div>
    </div>
    <?php if ($event_show_cta): ?>
    <div class="row cta visible-xs visible-sm">
        <span>
            <a href="<?php echo $event_cta_href; ?>" class="redButton"><?php echo $event_cta_button_text; ?></a>
        </span>
    </div>
    <?php endif; ?>
</div>
