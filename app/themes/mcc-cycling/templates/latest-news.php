<?php
    // Set the background image
    $news_bg_img = get_redux_field('bg_news');

    if (isset($news_bg_img['url'])) {
        $news_bg_img = $news_bg_img['url'];
    } else {
        $news_bg_img = '';
    }
?>

<div class="container section" data-stellar-background-ratio="0.5" id="latest-news" style="background-image: url('<?php echo $news_bg_img; ?>');">
    <div class="row">
        <div class="col-xs-12 col-lg-4">
            <h1 class="page-title">Latest News</h1>
        </div>
        <div class="col-xs-12 col-lg-8 content">
            <?php
                // Setup new post
                mcc_show_latest_post();
            ?>
        </div>
    </div>
</div>
