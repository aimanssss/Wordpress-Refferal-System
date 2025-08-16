<?php
/**
 * Plugin Name: Seasonal Referral System
 * Plugin URI:  https://example.com
 * Description: Seasonal affiliate and referral tracking system with seasonal campaigns and payouts.
 * Version:     0.1.0
 * Author:      Example Author
 * Author URI:  https://example.com
 * Text Domain: seasonal-referral-system
 * Requires PHP: 8.1
 * Requires at least: 6.6
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'SRS_FILE', __FILE__ );
define( 'SRS_PATH', plugin_dir_path( __FILE__ ) );
require_once SRS_PATH . 'includes/Autoloader.php';
Seasonal\ReferralSystem\Autoloader::register();

register_activation_hook( __FILE__, [ Seasonal\ReferralSystem\Database\Installer::class, 'activate' ] );
register_deactivation_hook( __FILE__, [ Seasonal\ReferralSystem\Database\Installer::class, 'deactivate' ] );
register_uninstall_hook( __FILE__, [ Seasonal\ReferralSystem\Database\Installer::class, 'uninstall' ] );

Seasonal\ReferralSystem\Plugin::instance();
