<?php
namespace Seasonal\ReferralSystem;

/**
 * Simple PSR-4 autoloader for plugin classes.
 */
class Autoloader {
    public static function register(): void {
        spl_autoload_register( [ __CLASS__, 'autoload' ] );
    }

    private static function autoload( string $class ): void {
        $prefix = __NAMESPACE__ . '\\';
        if ( strncmp( $prefix, $class, strlen( $prefix ) ) !== 0 ) {
            return;
        }
        $relative = substr( $class, strlen( $prefix ) );
        $relative = str_replace( '\\', '/', $relative );
        $file     = SRS_PATH . 'includes/' . $relative . '.php';
        if ( file_exists( $file ) ) {
            require_once $file;
        }
    }
}
