<?php
namespace Seasonal\ReferralSystem\Admin;

use WP_List_Table;

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class AffiliatesTable extends WP_List_Table {
    public function get_columns(): array {
        return [
            'id'     => __( 'ID', 'seasonal-referral-system' ),
            'user'   => __( 'User', 'seasonal-referral-system' ),
            'code'   => __( 'Code', 'seasonal-referral-system' ),
            'status' => __( 'Status', 'seasonal-referral-system' ),
        ];
    }

    protected function get_sortable_columns(): array {
        return [ 'id' => [ 'id', false ] ];
    }

    public function prepare_items(): void {
        global $wpdb;
        $table   = $wpdb->prefix . 'srs_affiliates';
        $results = $wpdb->get_results( "SELECT id, user_id, code, status FROM {$table}", ARRAY_A );
        $this->items = array_map(
            function ( $row ) {
                $user = get_user_by( 'id', (int) $row['user_id'] );
                $row['user'] = $user ? $user->user_login : '';
                return $row;
            },
            $results
        );
        $this->_column_headers = [ $this->get_columns(), [], $this->get_sortable_columns() ];
    }

    public function column_default( $item, $column_name ) {
        return esc_html( $item[ $column_name ] ?? '' );
    }
}
