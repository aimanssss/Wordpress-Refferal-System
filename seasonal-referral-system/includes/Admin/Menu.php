<?php
namespace Seasonal\ReferralSystem\Admin;

use Seasonal\ReferralSystem\Admin\AffiliatesTable;
use Seasonal\ReferralSystem\Admin\Settings;

class Menu {
    private static ?Menu $instance = null;

    public static function instance(): Menu {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'admin_menu', [ $this, 'register_menu' ] );
    }

    public function register_menu(): void {
        add_menu_page(
            __( 'Seasonal Referrals', 'seasonal-referral-system' ),
            __( 'Seasonal Referrals', 'seasonal-referral-system' ),
            'srs_manage',
            'srs_affiliates',
            [ $this, 'render_affiliates_page' ],
            'dashicons-groups'
        );
        add_submenu_page(
            'srs_affiliates',
            __( 'Settings', 'seasonal-referral-system' ),
            __( 'Settings', 'seasonal-referral-system' ),
            'srs_manage',
            'srs_settings',
            [ $this, 'render_settings_page' ]
        );
    }

    public function render_affiliates_page(): void {
        if ( ! current_user_can( 'srs_manage' ) ) {
            wp_die( esc_html__( 'You do not have permission.', 'seasonal-referral-system' ) );
        }
        $table = new AffiliatesTable();
        $table->prepare_items();
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Affiliates', 'seasonal-referral-system' ); ?></h1>
            <form method="post">
                <?php $table->display(); ?>
            </form>
        </div>
        <?php
    }

    public function render_settings_page(): void {
        if ( ! current_user_can( 'srs_manage' ) ) {
            wp_die( esc_html__( 'You do not have permission.', 'seasonal-referral-system' ) );
        }
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Settings', 'seasonal-referral-system' ); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields( 'srs_settings' );
                do_settings_sections( 'srs_settings' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}
