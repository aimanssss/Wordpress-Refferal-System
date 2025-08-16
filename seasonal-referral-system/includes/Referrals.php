<?php
namespace Seasonal\ReferralSystem;

/**
 * Helper to record referrals and commissions.
 */
class Referrals {
    public static function record( int $affiliate_id, float $amount, string $reference = '', int $season_id = 0 ): int {
        global $wpdb;
        $season_id  = $season_id ?: self::current_season_id();
        $rate       = self::get_commission_rate( $affiliate_id, $season_id );
        $commission = $rate['type'] === 'percent' ? ( $amount * $rate['value'] / 100 ) : $rate['value'];
        $wpdb->insert(
            $wpdb->prefix . 'srs_referrals',
            [
                'affiliate_id' => $affiliate_id,
                'season_id'    => $season_id,
                'reference'    => $reference,
                'amount'       => $amount,
                'commission'   => $commission,
                'status'       => 'pending',
                'created_at'   => current_time( 'mysql' ),
            ]
        );
        return (int) $wpdb->insert_id;
    }

    private static function current_season_id(): int {
        global $wpdb;
        $now = current_time( 'mysql' );
        $id  = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}srs_seasons WHERE active = 1 AND %s BETWEEN date_start AND date_end ORDER BY id DESC LIMIT 1", $now ) );
        return (int) $id;
    }

    private static function get_commission_rate( int $affiliate_id, int $season_id ): array {
        global $wpdb;
        $season = $wpdb->get_row( $wpdb->prepare( "SELECT default_rate_type, default_rate_value FROM {$wpdb->prefix}srs_seasons WHERE id = %d", $season_id ), ARRAY_A );
        if ( ! $season ) {
            $season = [ 'default_rate_type' => 'percent', 'default_rate_value' => 0 ];
        }
        return [ 'type' => $season['default_rate_type'], 'value' => (float) $season['default_rate_value'] ];
    }
}
