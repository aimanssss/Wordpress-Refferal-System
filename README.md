# Seasonal Referral System

A seasonal affiliate and referral tracking plugin for WordPress. It supports seasonal campaigns, affiliate dashboards, REST API endpoints and commission tracking.

## Setup
1. Upload the `seasonal-referral-system` folder to your `wp-content/plugins` directory.
2. Activate **Seasonal Referral System** from the WordPress Plugins screen.
3. Adjust settings under **Seasonal Referrals → Settings**.

## Shortcodes
- `[srs_referral_link]` – outputs the logged-in user's referral URL.
- `[srs_affiliate_dashboard]` – shows basic affiliate stats and referral link.

## REST API
Fetch affiliate information:
```
GET /wp-json/srs/v1/affiliate/<id>
```
Requires the `srs_manage` capability.

## Recording Referrals
Programmatically record a referral:
```php
use Seasonal\ReferralSystem\Referrals;

// $affiliate_id is the ID from the srs_affiliates table.
Referrals::record( $affiliate_id, 100.00, 'ORDER123' );
```

## Hooks
- `srs_cookie_days` (option) – number of days to store referral cookie.

## WooCommerce
The plugin provides a helper to record referrals. WooCommerce orders can call `Referrals::record()` when an order completes to generate a referral entry.

## License
GPL-2.0-or-later
