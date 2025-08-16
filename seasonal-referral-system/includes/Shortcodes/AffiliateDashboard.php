<?php
namespace Seasonal\ReferralSystem\Shortcodes;

use Seasonal\ReferralSystem\AffiliateManager;

class AffiliateDashboard {
    public static function register(): void {
        add_shortcode( 'srs_affiliate_dashboard', [ __CLASS__, 'render' ] );
    }

    public static function render(): string {
        if ( ! is_user_logged_in() ) {
            return '';
        }
        global $wpdb;
        $user_id      = get_current_user_id();
        $code         = AffiliateManager::get_affiliate_code( $user_id );
        $url          = AffiliateManager::get_referral_url( $user_id );
        $aff_table    = $wpdb->prefix . 'srs_affiliates';
        $affiliate_id = (int) $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$aff_table} WHERE user_id = %d", $user_id ) );
        $clicks       = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->prefix}srs_clicks WHERE affiliate_id = %d", $affiliate_id ) );
        $referrals    = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->prefix}srs_referrals WHERE affiliate_id = %d", $affiliate_id ) );
        $commission   = (float) $wpdb->get_var( $wpdb->prepare( "SELECT SUM(commission) FROM {$wpdb->prefix}srs_referrals WHERE affiliate_id = %d AND status = 'approved'", $affiliate_id ) );

        ob_start();
        ?>
        <div class="srs-dashboard">
            <p><?php echo esc_html__( 'Your affiliate code:', 'seasonal-referral-system' ) . ' ' . esc_html( $code ); ?></p>
            <p><?php echo esc_html__( 'Referral URL:', 'seasonal-referral-system' ) . ' ' . esc_url( $url ); ?></p>
            <ul>
                <li><?php echo esc_html__( 'Clicks:', 'seasonal-referral-system' ) . ' ' . esc_html( $clicks ); ?></li>
                <li><?php echo esc_html__( 'Referrals:', 'seasonal-referral-system' ) . ' ' . esc_html( $referrals ); ?></li>
                <li><?php echo esc_html__( 'Commission:', 'seasonal-referral-system' ) . ' ' . esc_html( number_format_i18n( $commission, 2 ) ); ?></li>
            </ul>
        </div>
        <?php
        return (string) ob_get_clean();
    }
}
