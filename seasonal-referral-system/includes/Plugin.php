<?php
namespace Seasonal\ReferralSystem;

use Seasonal\ReferralSystem\Admin\Menu;
use Seasonal\ReferralSystem\Admin\Settings;
use Seasonal\ReferralSystem\Shortcodes\ReferralLink;
use Seasonal\ReferralSystem\Shortcodes\AffiliateDashboard;
use Seasonal\ReferralSystem\Rest\AffiliatesController;

/**
 * Main plugin bootstrap.
 */
class Plugin {
    private static ?Plugin $instance = null;

    public static function instance(): Plugin {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'init', [ $this, 'init' ] );
        add_action( 'init', [ Tracking::class, 'maybe_set_cookie' ] );
        Menu::instance();
        Settings::register();
        ReferralLink::register();
        AffiliateDashboard::register();
        AffiliatesController::register_routes();
    }

    public function init(): void {
        load_plugin_textdomain( 'seasonal-referral-system', false, dirname( plugin_basename( SRS_FILE ) ) . '/languages/' );
    }
}
