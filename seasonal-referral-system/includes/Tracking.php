<?php
namespace Seasonal\ReferralSystem;

/**
 * Handle referral tracking via query parameter and cookie.
 */
class Tracking {
    const COOKIE = 'srs_ref';

    public static function maybe_set_cookie(): void {
        if ( isset( $_GET['ref'] ) ) {
            $code = sanitize_text_field( wp_unslash( $_GET['ref'] ) );
            $days = (int) get_option( 'srs_cookie_days', 30 );
            setcookie( self::COOKIE, $code, time() + DAY_IN_SECONDS * $days, COOKIEPATH, COOKIE_DOMAIN );
        }
    }

    public static function get_code_from_cookie(): ?string {
        return isset( $_COOKIE[ self::COOKIE ] ) ? sanitize_text_field( wp_unslash( $_COOKIE[ self::COOKIE ] ) ) : null;
    }
}
