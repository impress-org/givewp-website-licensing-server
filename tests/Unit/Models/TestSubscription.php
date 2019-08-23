<?php

namespace Tests\Unit\Subscription;

use App\Models\Subscription;
use App\Repositories\Subscriptions;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;
use function Tests\Helpers\getSubscriptionData;

class TestSubscription extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var Subscriptions $license
     * @access private
     */
    private $license;

    public function setUp(): void
    {
        parent::setUp();

        $this->license = new Subscriptions();
    }

    /**
     * @covers \App\Models\Subscription::store
     */
    public function testStore()
    {
        $license_key = 'abc';

        /**
         * Case: if license does not exist
         */
        /* @var Subscription|null $output */
        $output = $this->license->get($license_key);

        $this->assertEquals(null, $output);

        /**
         * Case: If license exist
         */
        $subscriptionData = getSubscriptionData(['id' => mt_rand(), 'license_key' => 'abc']);

        Subscription::store($subscriptionData['license_key'], $subscriptionData);

        $output = $this->license->get($license_key);

        $this->assertInstanceOf(Subscription::class, $output);
        $this->assertEquals($subscriptionData['license_key'], $output->license);
        $this->assertEquals($subscriptionData, $output->data);
    }
}
