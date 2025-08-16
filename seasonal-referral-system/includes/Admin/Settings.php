<?php
namespace Seasonal\ReferralSystem\Admin;

class Settings {
    public static function register(): void {
        add_action( 'admin_init', [ __CLASS__, 'settings' ] );
    }

    public static function settings(): void {
        register_setting( 'srs_settings', 'srs_cookie_days', [
            'type'              => 'integer',
            'sanitize_callback' => 'absint',
            'default'           => 30,
        ] );

        add_settings_section( 'srs_general', __( 'General', 'seasonal-referral-system' ), '__return_false', 'srs_settings' );

        add_settings_field(
            'srs_cookie_days',
            __( 'Cookie days', 'seasonal-referral-system' ),
            function (): void {
                $val = (int) get_option( 'srs_cookie_days', 30 );
                echo '<input type="number" name="srs_cookie_days" value="' . esc_attr( $val ) . '" />';
            },
            'srs_settings',
            'srs_general'
        );
    }
}
