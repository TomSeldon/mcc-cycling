<?php
/**
 * Welcome Page Class
 *
 * Shows a feature overview for the new version (major).
 *
 * @author 		AJDE
 * @category 	Admin
 * @package 	EventON/Admin
 * @version     1.0.0
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class EVO_Welcome_Page {


	public function __construct() {
		
		add_action( 'admin_menu', array( $this, 'admin_menus') );
		add_action( 'admin_head', array( $this, 'admin_head' ) );
		add_action( 'admin_init', array( $this, 'welcome'    ) );
	}

	/**
	 * Add admin menus/screens
	 */
	public function admin_menus() {

		$welcome_page_title = __( 'Welcome to EventON', 'eventon' );

		// About
		$about = add_dashboard_page( $welcome_page_title, $welcome_page_title, 'manage_options', 'evo-about', array( $this, 'about_screen' ) );

		
		add_action( 'admin_print_styles-'. $about, array( $this, 'admin_css' ) );
	}

	/**
	 * admin_css function.
	 */
	public function admin_css() {
		wp_enqueue_style( 'eventon-activation', AJDE_EVCAL_URL.'/assets/css/activation.css' );
	}
	
	/**
	 * Add styles just for this page, and remove dashboard page links.
	 */
	public function admin_head() {
		global $eventon;

		remove_submenu_page( 'index.php', 'evo-about' );
		
		?>
		<style type="text/css">
			
		</style>
		<?php
	}
	
	/**
	 * Into text/links shown on all about pages.
	 */
	private function intro() {
		global $eventon;
		

		// Drop minor version if 0
		//$major_version = substr( $eventon->version, 0, 3 );
		
	?>
		
		<div id='eventon_welcome_header'>
			
			<p class='logo'><img src='<?php echo AJDE_EVCAL_URL?>/assets/images/welcome/welcome_screen_logo.jpg'/><span>WordPress Event Calendar</span></p>
			<p class='h3'>
			<?php
				if(!empty($_GET['evo-updated']))
					$message = __( 'Thank you for updating eventON to ver ', 'eventon' );
				else
					$message = __( 'Thank you for purchasing eventON ver ', 'eventon' );
					
				printf( __( '%s%s', 'eventon' ), $message,	$eventon->version );
			?></p>			
			<p class='h4'><?php 
				if(!empty($_GET['evo-updated']))
					printf( __( 'We hope you will enjoy the new features we have added!','eventon'));
				else
					printf( __( 'We hope you will enjoy eventON - an event calendar plugin for WordPress!','eventon'));
			?></p>
			
		</div>
		

		<p class="eventon-actions">
			<a href="<?php echo admin_url('admin.php?page=eventon'); ?>" class="evo_admin_btn btn_prime"><?php _e( 'Settings', 'eventon' ); ?></a>
			
			<a class="evo_admin_btn btn_prime" href="http://www.myeventon.com/documentation/" target='_blank'><?php _e( 'Documentation', 'eventon' ); ?></a>
			
			<a class="evo_admin_btn btn_prime" href="http://www.myeventon.com/support/" target='_blank'><?php _e( 'Support', 'eventon' ); ?></a>

			<a class="evo_admin_btn btn_prime" href="http://www.myeventon.com/news/" target='_blank'><?php _e( 'News', 'eventon' ); ?></a>
			<a class="evo_admin_btn btn_prime" href="http://www.myeventon.com/documentation/eventon-changelog/" target='_blank'><?php _e( 'Changelog', 'eventon' ); ?></a>
			
		</p>
		<div class='eventon-welcome-twitter'>
			<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://www.myeventon.com/" data-text="Event Calendar Plugin for WordPress." data-via="EventON" data-size="large" data-hashtags="ashanjay">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</div>
		
		<?php /*
		<h2 class="nav-tab-wrapper">
			<a class="nav-tab <?php if ( $_GET['page'] == 'evo-about' ) echo 'nav-tab-active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'evo-about' ), 'index.php' ) ) ); ?>">
				<?php _e( "What's New", 'eventon' ); ?>			
			</a>
		</h2>
		<?php */
	}
	
	/**
	 * Output the about screen.
	 */
	public function about_screen() {
		global $eventon;
		?>
		<div class="wrap about-wrap eventon-welcome-box">

			<?php $this->intro(); ?>

			<!--<div class="changelog point-releases"></div>-->


			<div class="return-to-dashboard">
				<a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'eventon' ), 'admin.php' ) ) ); ?>"><?php _e( 'Go to myeventon Settings', 'eventon' ); ?></a>
			</div>
		</div>
		<?php


			/*
		?>
		<div class="wrap about-wrap">

			<?php $this->intro(); ?>

			<!--<div class="changelog point-releases"></div>-->

			<div class="changelog">

				
				<h3><?php _e( 'What is new?', 'eventon' ); ?></h3>

				<div class="feature-section col two-col">

					<div>
						<img src="<?php echo AJDE_EVCAL_URL . '/assets/images/welcome/001.jpg'; ?>" alt="Product panel screenshot" style="width: 99%; margin: 0 0 1em;" />
						<h4><?php _e( 'New Shortcode Box', 'eventon' ); ?></h4>
						<p><?php _e( 'Introducing the new super easy to use and very intuitive shortcode generator box for eventON along with new shortcode button for WYSIWYG editor.', 'eventon' ); ?></p>
					</div>

					<div class="last-feature">
						<img src="<?php echo AJDE_EVCAL_URL . '/assets/images/welcome/002.jpg'; ?>" alt="Order panel screenshot" style="width: 99%; margin: 0 0 1em;" />
						<h4><?php _e( 'Collapsable Settings', 'eventon' ); ?></h4>
						<p><?php _e( 'Event edit page settings meta boxes can now be collapsed for better space saving and have the page remember which ones you closed for next visit.', 'eventon' ); ?></p>
					</div>


				</div>
				<div class="feature-section col two-col">

					<div>
						<img src="<?php echo AJDE_EVCAL_URL . '/assets/images/welcome/003.jpg'; ?>" alt="Product panel screenshot" style="width: 99%; margin: 0 0 1em;" />
						<h4><?php _e( 'Rearrange EventCard', 'eventon' ); ?></h4>
						<p><?php _e( 'You can now rearrange the order in which to show eventCard data.', 'eventon' ); ?></p>
					</div>

					<div class="last-feature">
						<img src="<?php echo AJDE_EVCAL_URL . '/assets/images/welcome/004.jpg'; ?>" alt="Order panel screenshot" style="width: 99%; margin: 0 0 1em;" />
						<h4><?php _e( 'Dual Lingual Capability', 'eventon' ); ?></h4>
						<p><?php _e( 'You can now customize eventON text to have two different languages and choose which one to use on calendar.', 'eventon' ); ?></p>
					</div>


				</div>
				<div class="feature-section col three-col">

					<div>
						<img src="<?php echo AJDE_EVCAL_URL . '/assets/images/welcome/005.jpg'; ?>" alt="Product panel screenshot" style="width: 99%; margin: 0 0 1em;" />
						<h4><?php _e( 'ICS and get directions', 'eventon' ); ?></h4>
						<p><?php _e( "We've added ICS file download capability to events along with get directions from the location of the event direct into the eventCard.", 'eventon' ); ?></p>
					</div>

					<div>
						<img src="<?php echo AJDE_EVCAL_URL . '/assets/images/welcome/006.jpg'; ?>" alt="Order panel screenshot" style="width: 99%; margin: 0 0 1em;" />
						<h4><?php _e( 'Feature Events', 'eventon' ); ?></h4>
						<p><?php _e( 'You can now feature events and have the featured events show above all the other events.', 'eventon' ); ?></p>
					</div>

					

				</div>

				

			</div>

			<h3><?php _e( "A message from Ashan Jay", 'eventon' ); ?></h3>

			<div class="feature-section images-stagger-right">					
				<p>I want to personally thank you for purchasing eventON! Your support has allowed us to move eventON forward with amazing new features and fine tune our beautiful calendar design. We have so much more planned for eventON in the coming months. If you haven't already please take part in our <a href='https://groups.google.com/forum/#!forum/myeventon' target='_blank'>support forum</a> to make eventON a better plugin for tomorrow and voice your suggestions for future updates. -- <a target='_blank' href='http://www.myeventon.com/documentation/eventon-changelog/'><?php _e( 'The complete changelog', 'eventon' ); ?></a></p>
			</div>


			<div class="return-to-dashboard">
				<a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'eventon' ), 'admin.php' ) ) ); ?>"><?php _e( 'Go to myEventON Settings', 'eventon' ); ?></a>
			</div>
		</div>
		<?php

		*/
	}
	
	/**
	 * Sends user to the welcome page on first activation
	 */
	public function welcome() {

		// Bail if no activation redirect transient is set
	    if ( ! get_transient( '_evo_activation_redirect' )  )
			return;

		// Delete the redirect transient
		delete_transient( '_evo_activation_redirect' );

		// Bail if we are waiting to install or update via the interface update/install links
		if ( get_option( '_evo_needs_update' ) == 1  )
			return;

		// Bail if activating from network, or bulk, or within an iFrame
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) || defined( 'IFRAME_REQUEST' ) )
			return;
		
		// plugin is updated
		if ( ( isset( $_GET['action'] ) && 'upgrade-plugin' == $_GET['action'] ) && ( isset( $_GET['plugin'] ) && strstr( $_GET['plugin'], 'eventon.php' ) ) ){
			wp_safe_redirect( admin_url( 'index.php?page=evo-about&evo-updated=true' ) );
		
		// first time activation
		}else{
			wp_safe_redirect( admin_url( 'index.php?page=evo-about' ) );
		}
			
		
		exit;
	}	
	
	
	
}

new EVO_Welcome_Page();
?>