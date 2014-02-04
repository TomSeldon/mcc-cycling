<?php
/**
 * Enqueue scripts and stylesheets
 *
 * Enqueue stylesheets in the following order:
 * 1. /theme/assets/css/main.min.css
 *
 * Enqueue scripts in the following order:
 * 1. jquery-1.10.2.min.js via Google CDN
 * 2. /theme/assets/js/vendor/modernizr-2.7.0.min.js
 * 3. /theme/assets/js/main.min.js (in footer)
 */
function roots_scripts() {
  wp_enqueue_style('animate.css', get_template_directory_uri() . '/assets/css/animate.min.css', false, '794cb380366e3fd8ea9aaec75fe9a5ab');
  wp_enqueue_style('roots_main', get_template_directory_uri() . '/assets/css/main.min.css', false, 'da5946d48c65232d84dd901b3ff79d48');

  // jQuery is loaded using the same method from HTML5 Boilerplate:
  // Grab Google CDN's latest jQuery with a protocol relative URL; fallback to local if offline
  // It's kept in the header instead of footer to avoid conflicts with plugins.
  if (!is_admin() && current_theme_supports('jquery-cdn')) {
    wp_deregister_script('jquery');
    wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js', array(), null, false);
    add_filter('script_loader_src', 'roots_jquery_local_fallback', 10, 2);
  }

  if (is_single() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }

  wp_register_script('jquery-scrollwheel', '//cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.0.6/jquery.mousewheel.min.js');
  wp_register_script('mcustomscrollbar', get_template_directory_uri() . '/assets/js/vendor/jquery.mCustomScrollbar.js', array('jquery-scrollwheel'), null, false);
  wp_register_script('angular', '//ajax.googleapis.com/ajax/libs/angularjs/1.2.8/angular.min.js', array(), null, false);
  wp_register_script('modernizr', get_template_directory_uri() . '/assets/js/vendor/modernizr-2.7.0.min.js', array(), null, false);
  wp_register_script('roots_scripts', get_template_directory_uri() . '/assets/js/scripts.min.js', array('angular'), '402167dde8574e5bcc3ab2dcb404071c', true);
  wp_register_script('slimscroll', get_template_directory_uri() . '/assets/js/vendor/jquery.slimscroll.min.js', array('jquery'), null, true);
  wp_register_script('fullScreen', get_template_directory_uri() . '/assets/js/vendor/jquery.fullPage.min.js', array('jquery', 'jquery-ui-core', 'jquery-effects-core', 'slimscroll'), null, true);
  wp_register_script('addthisevent', 'http://js.addthisevent.com/atemay.js', array(), null, true);

  wp_enqueue_script('modernizr');
  wp_enqueue_script('jquery');
  wp_enqueue_script('fullScreen');
  wp_enqueue_script('mcustomscrollbar');
  wp_enqueue_script('roots_scripts');

  if (is_page_template('template-sportive.php')) {
      wp_enqueue_script('addthisevent');
  }
}
add_action('wp_enqueue_scripts', 'roots_scripts', 90);

function mcc_admin_scripts()
{
   // wp_enqueue_script('gmaps_autocomplete', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true');
}
add_action('admin_enqueue_scripts', 'mcc_admin_scripts');

// http://wordpress.stackexchange.com/a/12450
function roots_jquery_local_fallback($src, $handle = null) {
  static $add_jquery_fallback = false;

  if ($add_jquery_fallback) {
    echo '<script>window.jQuery || document.write(\'<script src="' . get_template_directory_uri() . '/assets/js/vendor/jquery-1.10.2.min.js"><\/script>\')</script>' . "\n";
    $add_jquery_fallback = false;
  }

  if ($handle === 'jquery') {
    $add_jquery_fallback = true;
  }

  return $src;
}
add_action('wp_head', 'roots_jquery_local_fallback');

function roots_google_analytics() { ?>
<script>
  (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
  function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
  e=o.createElement(i);r=o.getElementsByTagName(i)[0];
  e.src='//www.google-analytics.com/analytics.js';
  r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
  ga('create','<?php echo GOOGLE_ANALYTICS_ID; ?>');ga('send','pageview');
</script>

<?php }
if (GOOGLE_ANALYTICS_ID && !current_user_can('manage_options')) {
  add_action('wp_footer', 'roots_google_analytics', 20);
}
