<!DOCTYPE html>
<html <?php language_attributes(); ?> id="wp_maintenance_mode"<?php if ( is_rtl() ) echo ' dir="rtl"'; ?>>
<head>
	<?php
	if ( ! isset($value) ) {
		$value = WPMaintenanceMode::get_options();
		$unitvalues = $WPMaintenanceMode->case_unit($value['unit']);
	}
	
	$author = 'WP Maintenance Mode: Frank Bueltge, http://bueltge.de';
	$author = apply_filters( 'wm_meta_author', $author );
	
	$desc = get_bloginfo( 'name' ) . ' - ' . get_bloginfo( 'description' );
	$desc = apply_filters( 'wm_meta_description', $desc );
	
	$keywords = 'Maintenance Mode';
	$keywords = apply_filters( 'wm_meta_keywords', $keywords );
	
	if ( isset( $value['index'] ) && 1 === $value['index'] )
		$content = 'noindex, nofollow';
	else {
		$content = 'index, follow';
	}
	
	?>
	
	<title><?php if ( isset($value['title']) && ($value['title'] != '') ) echo stripslashes_deep( $value['title'] ); else { bloginfo('name'); echo ' - '; _e( 'Maintenance Mode', FB_WM_TEXTDOMAIN ); } ?></title>
	
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<meta name="author" content="<?php echo $author; ?>" />
	<meta name="description" content="<?php echo $desc; ?>" />
	<meta name="keywords" content="<?php echo $keywords; ?>" />
	<meta name="robots" content="<?php echo $content; ?>" />
	<link rel="Shortcut Icon" type="image/x-icon" href="<?php echo get_option('home'); ?>/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL . '/' . FB_WM_BASEDIR ?>/css/jquery.countdown.css" media="all" />
	
	<?php
	if ( ! defined('WP_CONTENT_URL') )
		define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
	if ( ! defined('WP_PLUGIN_URL') )
		define( 'WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins' );
	
	if ( ! defined('FB_WM_BASENAME') )
		define( 'FB_WM_BASENAME', plugin_basename(__FILE__) );
	if ( ! defined('FB_WM_BASEDIR') )
		define( 'FB_WM_BASEDIR', dirname( plugin_basename(__FILE__) ) );
	
	global $user_ID;
	
	get_currentuserinfo();
	$locale = get_locale();
	
	wm_head(); ?>
	
</head>

<body>
	
	<div id="header">
		<p><?php if ( isset($value['header']) && ($value['header'] != '') ) echo stripslashes_deep( $value['header'] ); else { bloginfo('name'); echo ' - '; bloginfo('description'); } ?></p>
	</div>

	<div id="content">
		
		<h1><?php if ( isset($value['heading']) && ($value['heading'] != '') ) echo stripslashes_deep( $value['heading'] ); else _e( 'Maintenance Mode', FB_WM_TEXTDOMAIN ); ?></h1>
		
		<?php wm_content();
		if ( isset( $value['admin_link'] ) && 1 === $value['admin_link'] ) {
			if ( isset($user_ID) && $user_ID ) {
				$adminlogin    = wp_logout_url();
				if ( isset($rolestatus) && 'norights' == $rolestatus )
					$adminloginmsg = '<h3>' . __( 'Access to the admin area blocked', FB_WM_TEXTDOMAIN ) . '</h3>';
				else
					$adminloginmsg = '';
				$adminloginstr = __( 'Admin-Logout', FB_WM_TEXTDOMAIN );
			} else {
				// Returns the Log In URL
				$adminlogin    = wp_login_url();
				$adminloginmsg = '';
				$adminloginstr = __( 'Admin-Login', FB_WM_TEXTDOMAIN );
			}
			echo $adminloginmsg;
		?>
		<div class="admin" onclick="location.href='<?php echo $adminlogin; ?>';" onkeypress="location.href='<?php echo $adminlogin; ?>';"><a href="<?php echo $adminlogin; ?>"><?php echo $adminloginstr; ?></a></div>
		<?php } ?>
		
	</div>
	
	<?php wm_footer();
	
	$td = WPMaintenanceMode::check_datetime();
	if ( isset($td[2]) && 0 !== $td[2] ) {
		$locale = substr($locale, 0, 2);
	?>
	
		<script type="text/javascript" src="<?php bloginfo('url') ?>/wp-includes/js/jquery/jquery.js"></script>
		<script type="text/javascript" src="<?php echo WPMaintenanceMode::get_plugins_url( 'js/jquery.countdown.pack.js', __FILE__ ); ?>"></script>
		<?php if ( @file_exists( FB_WM_BASE . '/js/jquery.countdown-' . $locale . '.js') ) { ?>
		<script type="text/javascript" src="<?php echo WPMaintenanceMode::get_plugins_url( 'js/jquery.countdown-' . $locale . '.js', __FILE__ ); ?>"></script>
		<?php } ?>
		
		<script type="text/javascript">
			jQuery(document).ready( function($){
				var austDay = new Date();
				// 'Years', 'Months', 'Weeks', 'Days', 'Hours', 'Minutes', 'Seconds'
				austDay = new Date(<?php echo $td[2]; ?>);
				$('#countdown').countdown({ until: austDay });
			});
		</script>
	<?php } ?>
</body>
</html>