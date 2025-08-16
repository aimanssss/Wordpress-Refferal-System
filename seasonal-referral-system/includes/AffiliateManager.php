<?php
namespace Seasonal\ReferralSystem;

use wpdb;

/**
 * Basic affiliate utilities.
 */
class AffiliateManager {
    public static function get_affiliate_code( int $user_id ): string {
        global $wpdb;
        $table = $wpdb->prefix . 'srs_affiliates';
        $code  = $wpdb->get_var( $wpdb->prepare( "SELECT code FROM {$table} WHERE user_id = %d", $user_id ) );
        if ( ! $code ) {
            $code = self::generate_code( $user_id );
            $wpdb->insert( $table, [
                'user_id' => $user_id,
                'code'    => $code,
                'status'  => 'active',
                'join_date' => current_time( 'mysql' ),
            ] );
        }
        return $code;
    }

    public static function get_referral_url( int $user_id ): string {
        $code = self::get_affiliate_code( $user_id );
        return add_query_arg( 'ref', $code, home_url( '/' ) );
    }

    private static function generate_code( int $user_id ): string {
        return 'u' . $user_id . wp_generate_password( 6, false );
    }
}
