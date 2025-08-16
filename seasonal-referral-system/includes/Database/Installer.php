<?php
namespace Seasonal\ReferralSystem\Database;

use wpdb;

/**
 * Install and uninstall database tables.
 */
class Installer {
    const VERSION = '1.0';

    public static function activate(): void {
        self::maybe_upgrade();
        \Seasonal\ReferralSystem\Roles::add_caps();
    }

    public static function deactivate(): void {
        // nothing for now
    }

    public static function uninstall(): void {
        global $wpdb;
        $tables = [
            $wpdb->prefix . 'srs_affiliates',
            $wpdb->prefix . 'srs_referrals',
            $wpdb->prefix . 'srs_clicks',
            $wpdb->prefix . 'srs_payouts',
            $wpdb->prefix . 'srs_seasons',
        ];
        foreach ( $tables as $table ) {
            $wpdb->query( "DROP TABLE IF EXISTS {$table}" );
        }
        delete_option( 'srs_db_version' );
        \Seasonal\ReferralSystem\Roles::remove_caps();
    }

    private static function maybe_upgrade(): void {
        $installed = get_option( 'srs_db_version' );
        if ( self::VERSION === $installed ) {
            return;
        }
        self::create_tables();
        update_option( 'srs_db_version', self::VERSION );
    }

    private static function create_tables(): void {
        global $wpdb;
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        $charset = $wpdb->get_charset_collate();

        $affiliates = "CREATE TABLE {$wpdb->prefix}srs_affiliates (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id BIGINT UNSIGNED NOT NULL,
            code VARCHAR(64) NOT NULL,
            status VARCHAR(20) NOT NULL DEFAULT 'active',
            join_date DATETIME NOT NULL,
            payment_method VARCHAR(100) DEFAULT '',
            payout_address VARCHAR(200) DEFAULT '',
            PRIMARY KEY  (id),
            KEY user_id (user_id),
            UNIQUE KEY code (code)
        ) {$charset};";

        $referrals = "CREATE TABLE {$wpdb->prefix}srs_referrals (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            affiliate_id BIGINT UNSIGNED NOT NULL,
            season_id BIGINT UNSIGNED NOT NULL,
            reference VARCHAR(191) DEFAULT '',
            amount DECIMAL(18,8) NOT NULL DEFAULT 0,
            commission DECIMAL(18,8) NOT NULL DEFAULT 0,
            status VARCHAR(20) NOT NULL DEFAULT 'pending',
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            KEY affiliate_id (affiliate_id),
            KEY season_id (season_id)
        ) {$charset};";

        $clicks = "CREATE TABLE {$wpdb->prefix}srs_clicks (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            affiliate_id BIGINT UNSIGNED NOT NULL,
            season_id BIGINT UNSIGNED NOT NULL,
            ip_hash CHAR(32) NOT NULL,
            user_agent_hash CHAR(32) NOT NULL,
            landed_url TEXT,
            referrer_url TEXT,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            KEY affiliate_id (affiliate_id)
        ) {$charset};";

        $payouts = "CREATE TABLE {$wpdb->prefix}srs_payouts (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            affiliate_id BIGINT UNSIGNED NOT NULL,
            total_commission DECIMAL(18,8) NOT NULL DEFAULT 0,
            fee DECIMAL(18,8) NOT NULL DEFAULT 0,
            status VARCHAR(20) NOT NULL DEFAULT 'requested',
            admin_note TEXT,
            created_at DATETIME NOT NULL,
            paid_at DATETIME NULL,
            PRIMARY KEY (id),
            KEY affiliate_id (affiliate_id)
        ) {$charset};";

        $seasons = "CREATE TABLE {$wpdb->prefix}srs_seasons (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(191) NOT NULL,
            date_start DATETIME NOT NULL,
            date_end DATETIME NOT NULL,
            is_recurring TINYINT(1) NOT NULL DEFAULT 0,
            active TINYINT(1) NOT NULL DEFAULT 1,
            default_rate_type VARCHAR(10) NOT NULL DEFAULT 'percent',
            default_rate_value DECIMAL(10,4) NOT NULL DEFAULT 0,
            PRIMARY KEY (id)
        ) {$charset};";

        dbDelta( $affiliates );
        dbDelta( $referrals );
        dbDelta( $clicks );
        dbDelta( $payouts );
        dbDelta( $seasons );
    }
}
