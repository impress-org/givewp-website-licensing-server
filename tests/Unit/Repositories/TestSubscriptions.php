<?php

namespace Test\Repositories;

use App\Models\Subscription;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Repositories\Subscriptions;

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
    public function testContainer()
    {
        $this->assertInstanceOf(
            Subscriptions::class,
            app(Subscriptions::class)
        );
    }

    /**
     * @cover \App\Repositories\Subscriptions::get
     */
    public function testGet()
    {
        $license_key = 'abc';

        /**
         * Case: if license does not exist
         */
        /* @var Subscriptions|null $output */
        $output = $this->subscription->get($license_key);

        $this->assertEquals(null, $output);

        /**
         * Case: If license exist
         */
        Subscription::store($license_key, ['dummy data']);

        $output = $this->subscription->get($license_key);

        $this->assertInstanceOf(Subscription::class, $output);
        $this->assertEquals($license_key, $output->license);
        $this->assertEquals(['dummy data'], $output->data);
    }

    /**
     * @covers \App\Repositories\Subscriptions::delete
     */
    public function testShouldGetZeroWhenDeleteNonExistingSubscription(): void
    {
        $result = app(Subscriptions::class)->delete('abc');

        $this->assertEquals(0, $result);
        $this->notSeeInDatabase('subscriptions', array( 'license' => 'abc'));
    }

    /**
     * @covers \App\Repositories\Subscriptions::delete
     */
    public function testShouldGetBoolWhenDeletesubscription(): void
    {
        $subscription = Subscription::store('abc', ['dummy data']);
        $result = app(Subscriptions::class)->delete('abc');

        $this->assertEquals($subscription->id, $result);
        $this->notSeeInDatabase('subscriptions', array( 'license' => 'abc'));
    }
}
