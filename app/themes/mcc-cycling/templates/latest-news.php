<?php
    // Set the background image
    $news_bg_img = get_redux_field('bg_news');

    if (isset($news_bg_img['url'])) {
        $news_bg_img = $news_bg_img['url'];
    } else {
        $news_bg_img = '';
    }
?>

<div class="container" id="latest-news" style="background-image: url('<?php echo $news_bg_img; ?>');">
    <div class="row">
        <div class="col-sm-4">
            <h1>Latest News</h1>
        </div>
    </div>
    <div class="row news">

    </div>
    <div class="row">
        <div class="col-lg-12 content">
            <?php the_field('news_content'); ?>
        </div>
    </div>
</div>
