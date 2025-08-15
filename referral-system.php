<?php
/*
Plugin Name: Simple Referral System
Description: Adds a basic referral system with shortcode to generate user referral links.
Version: 1.0.0
Author: OpenAI
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Handle incoming referral links by saving the referring user ID in a cookie.
 */
function srs_handle_referral() {
    if ( isset( $_GET['ref'] ) ) {
        $ref_id = intval( $_GET['ref'] );
        // Store referrer ID for 30 days.
        setcookie( 'srs_ref', $ref_id, time() + 30 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
    }
}
add_action( 'init', 'srs_handle_referral' );

/**
 * When a new user registers, save the referrer ID (if any) to the user's meta.
 *
 * @param int $user_id ID of the newly registered user.
 */
function srs_save_referral_meta( $user_id ) {
    if ( isset( $_COOKIE['srs_ref'] ) ) {
        $referrer_id = intval( $_COOKIE['srs_ref'] );
        update_user_meta( $user_id, 'srs_referred_by', $referrer_id );
        // Clear the cookie after use.
        setcookie( 'srs_ref', '', time() - DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
    }
}
add_action( 'user_register', 'srs_save_referral_meta' );

/**
 * Shortcode that outputs the current user's referral link.
 *
 * Usage: [referral_link]
 *
 * @return string Referral URL or empty string if user not logged in.
 */
function srs_referral_link_shortcode() {
    if ( ! is_user_logged_in() ) {
        return '';
    }

    $user_id  = get_current_user_id();
    $ref_link = add_query_arg( 'ref', $user_id, home_url( '/' ) );

    return esc_url( $ref_link );
}
add_shortcode( 'referral_link', 'srs_referral_link_shortcode' );
