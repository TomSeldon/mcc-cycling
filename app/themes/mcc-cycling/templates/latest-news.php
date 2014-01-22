<?php
    // Set the background image
    $news_bg_img = get_redux_field('bg_news');

    if (isset($news_bg_img['url'])) {
        $news_bg_img = $news_bg_img['url'];
    } else {
        $news_bg_img = '';
    }
?>

<div class="container section" id="latest-news" style="background-image: url('<?php echo $news_bg_img; ?>');">
    <div class="row">
        <div class="col-sm-4">
            <h1 class="page-title">Latest News</h1>
        </div>
        <div class="col-sm-8 content">
            <?php
                // Setup new post
                mcc_show_latest_post();
            ?>
        </div>
    </div>
</div>
