<?php
/**
 * Plugin Name: Woocommerce Stock Notifier
 * Plugin URI: 
 * Description: Test dev.
 * Author: test
 * Version: 1.0
 * Requires at least: 5.0
 * Tested up to: 6.4.1
 * WC requires at least: 7.2
 * WC tested up to: 8.3.1
 * Author URI: 
 * Text Domain: wc-stock-notifier
 * Domain Path: /languages/
 */

if ( ! class_exists( 'WC_Dependencies_Stock_Notifier' ) )
	require_once 'classes/class-wc-stock-notifier-dependencies.php';

//functions
require_once 'includes/wc-stock-notifier-core-functions.php';
require_once 'includes/wc-stock-notifier-setting-functions.php';

require_once 'config.php';
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! WC_Stock_Notifier_Dependencies::woocommerce_plugin_active_check() ) {
  add_action( 'admin_notices', 'woocommerce_inactive_notice' );
}

/**
 * Declare support for 'High-Performance order storage (COT)' in WooCommerce
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    add_action(
	'before_woocommerce_init',
		function () {
			if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', plugin_basename( __FILE__ ), true );
			}
		}
	);
}


// add_filter('plugin_action_links_' . plugin_basename( __FILE__ ), 'WC_Stock_Notifier_settings');

// function WC_Stock_Notifier_settings($links) {
//     $plugin_links = array(
//         '<a href="' . admin_url('admin.php?page=woo-stock-alert-setting#&tab=settings&subtab=general') . '">' . __('Settings', 'wc-stock-notifier') . '</a>',
//         '<a href="https://multivendorx.com/support-forum/forum/product-stock-manager-notifier-for-woocommerce/?utm_source=wordpress.org&utm_medium=freelandingpage&utm_campaign=products-stock-manager" target="_blank">' . __('Support', 'wc-stock-notifier') . '</a>',
//         '<a href="https://multivendorx.com/docs/knowledgebase/products-stock-manager-notifier-for-woocommerce/?utm_source=wordpress.org&utm_medium=freelandingpage&utm_campaign=products-stock-manager" target="_blank">' . __('Docs', 'wc-stock-notifier') . '</a>'
//     );
//     if (apply_filters('is_stock_alert_pro_inactive', true)) {
//     	$links['go_pro'] = '<a href="' . WC_STOCK_NOTIFIER_PRO_SHOP_URL . '" class="stock-alert-pro-plugin" target="_blank">' . __('Get Pro', 'wc-stock-notifier') . '</a>';
//     }
//     return array_merge($plugin_links, $links);
// }

if ( ! class_exists( 'WC_Stock_Notifier' ) && WC_Stock_Notifier_Dependencies::woocommerce_plugin_active_check() ) {
	require_once('classes/class-wc-stock-notifier.php');
	global $WC_Stock_Notifier;
	$WC_Stock_Notifier = new WC_Stock_Notifier( __FILE__ );
	$GLOBALS['WC_Stock_Notifier'] = $WC_Stock_Notifier;
	// Activation Hooks
	register_activation_hook( __FILE__, [ 'WC_Stock_Notifier', 'activate_wc_stock_notifier' ] );
	// Deactivation Hooks
	register_deactivation_hook( __FILE__, [ 'WC_Stock_Notifier', 'deactivate_wc_stock_notifier' ] );
}