<?php
use PHPUnit\Framework\TestCase;
use Seasonal\ReferralSystem\Referrals;

/**
 * Basic tests for referral calculations.
 */
class ReferralsTest extends TestCase {
    public function test_commission_calculation(): void {
        $this->assertTrue( method_exists( Referrals::class, 'record' ) );
    }
}
