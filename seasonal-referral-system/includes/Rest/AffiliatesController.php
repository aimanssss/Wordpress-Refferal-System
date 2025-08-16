<?php
namespace Seasonal\ReferralSystem\Rest;

use WP_Error;
use WP_REST_Request;

class AffiliatesController {
    const NAMESPACE = 'srs/v1';

    public static function register_routes(): void {
        add_action( 'rest_api_init', [ __CLASS__, 'routes' ] );
    }

    public static function routes(): void {
        register_rest_route(
            self::NAMESPACE,
            '/affiliate/(?P<id>\d+)',
            [
                'methods'             => 'GET',
                'callback'            => [ __CLASS__, 'get_affiliate' ],
                'permission_callback' => function (): bool {
                    return current_user_can( 'srs_manage' );
                },
            ]
        );
    }

    public static function get_affiliate( WP_REST_Request $request ) {
        global $wpdb;
        $id   = (int) $request['id'];
        $row  = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}srs_affiliates WHERE id = %d", $id ), ARRAY_A );
        if ( ! $row ) {
            return new WP_Error( 'not_found', __( 'Affiliate not found', 'seasonal-referral-system' ), [ 'status' => 404 ] );
        }
        return rest_ensure_response( $row );
    }
}
