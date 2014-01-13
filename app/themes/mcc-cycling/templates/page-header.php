<?php
    $subtitle = apply_filters('page_subtitle', get_field('sub_title'));
?>

<div class="row headings animated fadeInDown">
    <h1><?php echo roots_title(); ?></h1>

    <?php if ($subtitle !== false): ?>
    <h2><?php echo $subtitle; ?></h2>
    <?php endif; ?>
</div>