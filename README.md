# Seasonal Referral System

Seasonal Referral System is a WordPress plugin that manages affiliates, tracks referrals and clicks, and supports seasonal campaigns with configurable commission rates and payouts.

## Setup
1. Upload the `seasonal-referral-system` folder to the `/wp-content/plugins/` directory.
2. Activate **Seasonal Referral System** through the **Plugins** menu in WordPress.
3. Navigate to **Seasonal Referrals → Settings** to configure options such as cookie duration, auto-approve affiliates, and default commissions.
4. (Optional) Enable WooCommerce integration in the settings.

## Shortcodes
- `[srs_referral_link]` – Outputs the logged-in user’s referral URL.
- `[referral_link]` – Legacy alias for `[srs_referral_link]`.
- `[srs_affiliate_dashboard]` – Displays affiliate statistics and a payout request form.

## REST API
Endpoints are available under `/wp-json/srs/v1` with capability and nonce checks.

Example:
```
GET /wp-json/srs/v1/affiliate/<id>
```

## Programmatic Referral Recording
Record a referral in custom code:

```php
use Seasonal\ReferralSystem\Referrals;

// $affiliate_id is the ID from the srs_affiliates table.
Referrals::record( $affiliate_id, 100.00, 'ORDER123' );
```

## WooCommerce Integration
When enabled, the plugin hooks into WooCommerce order completion. If a customer checks out with a valid referral code or cookie, a referral record is created using the order ID and calculated commission. Self‑referrals are ignored.

## Hooks and Filters
- `srs_cookie_days` – Filter the number of days the referral cookie persists.

## License
GPL-2.0-or-later
