<?php

namespace Test\Repositories;

use App\Models\Subscription;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Repositories\Subscriptions;
use function Tests\Helpers\getSubscriptionData;

/**
 * Class TestSubscriptions
 * @package Test\Repositories
 */
class TestSubscriptions extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var Subscriptions $subscription
     * @access private
     */
    private $subscription;

    public function setUp(): void
    {
        parent::setUp();

        $this->subscription = new Subscriptions();
    }

    /**
     * Checks to see that the repository is injected into the container
     */
    public function testContainer(): void
    {
        $this->assertInstanceOf(Subscriptions::class, app(Subscriptions::class));
    }

    /**
     * @cover \App\Repositories\Subscriptions::get
     */
    public function testShouldReturnNullWhenGetNonExistingSubscription(): void
    {
        $license_key = 'abc';
        $output      = $this->subscription->get($license_key);

        $this->assertEquals(null, $output);
    }

    /**
     * @cover \App\Repositories\Subscriptions::get
     */
    public function testShouldReturnSubscriptionModelWhenGetSubscription(): void
    {
        $subscriptionData = getSubscriptionData(['id' => mt_rand(), 'license_key' => 'abc']);

        Subscription::store($subscriptionData['license_key'], $subscriptionData);

        $output = $this->subscription->get($subscriptionData['license_key']);

        $this->assertInstanceOf(Subscription::class, $output);
        $this->assertEquals($subscriptionData['license_key'], $output->license);
        $this->assertEquals($subscriptionData, $output->data);
    }

    /**
     * @covers \App\Repositories\Subscriptions::delete
     */
    public function testShouldGetZeroWhenDeleteNonExistingSubscription(): void
    {
        $result = app(Subscriptions::class)->delete('abc');

        $this->assertEquals(0, $result);
        $this->notSeeInDatabase('subscriptions', array('license' => 'abc'));
    }

    /**
     * @covers \App\Repositories\Subscriptions::delete
     */
    public function testShouldGetOneWhenDeleteSubscription(): void
    {
        $subscriptionData = getSubscriptionData(['id' => mt_rand(), 'license_key' => 'abc']);

        Subscription::store($subscriptionData['license_key'], $subscriptionData);

        $result = app(Subscriptions::class)->delete('abc');

        $this->assertEquals(1, $result);
        $this->notSeeInDatabase('subscriptions', array('license' => $subscriptionData['license_key']));
    }

    /**
     * @covers \App\Repositories\Subscriptions:deleteBySubscriptionID
     */
    public function testShouldReturnOneWhenDeleteSubscriptionByID(): void
    {
        $subscriptionData = getSubscriptionData(['id' => mt_rand(), 'license_key' => 'abc']);

        Subscription::store($subscriptionData['license_key'], $subscriptionData);

        $result = app(Subscriptions::class)->deleteBySubscriptionID($subscriptionData['id']);

        $this->assertEquals(1, $result);
        $this->notSeeInDatabase('subscriptions', array('license' => $subscriptionData['license_key']));
    }
}
