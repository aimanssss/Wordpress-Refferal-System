<?php
namespace Seasonal\ReferralSystem\Shortcodes;

use Seasonal\ReferralSystem\AffiliateManager;

class ReferralLink {
    public static function register(): void {
        add_shortcode( 'srs_referral_link', [ __CLASS__, 'render' ] );
    }

    public static function render(): string {
        if ( ! is_user_logged_in() ) {
            return '';
        }
        $url = AffiliateManager::get_referral_url( get_current_user_id() );
        return esc_url( $url );
    }
}
