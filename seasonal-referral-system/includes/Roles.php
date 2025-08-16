<?php
namespace Seasonal\ReferralSystem;

class Roles {
    public static function add_caps(): void {
        $admin = get_role( 'administrator' );
        if ( $admin ) {
            $admin->add_cap( 'srs_manage' );
            $admin->add_cap( 'srs_view_reports' );
        }
        $subscriber = get_role( 'subscriber' );
        if ( $subscriber ) {
            $subscriber->add_cap( 'srs_affiliate' );
        }
    }

    public static function remove_caps(): void {
        $roles = [ 'administrator', 'subscriber' ];
        foreach ( $roles as $role_name ) {
            $role = get_role( $role_name );
            if ( $role ) {
                $role->remove_cap( 'srs_manage' );
                $role->remove_cap( 'srs_view_reports' );
                $role->remove_cap( 'srs_affiliate' );
            }
        }
    }
}
