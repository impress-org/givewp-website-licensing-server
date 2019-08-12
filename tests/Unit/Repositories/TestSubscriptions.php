<?php

namespace Test\Repositories;

use App\Models\Subscription;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Repositories\Subscriptions;

/**
 * Class Subscriptions
 *
 * Repository for handling subscriptions.
 *
 * @since   0.1.0
 *
 * @package App\Repositories
 */
class TestSubscriptions extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var Subscriptions $subscription
     * @access private
     */
    private $subscription;

    function setUp(): void
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
        $this->assertEquals(array('dummy data'), $output->data);
    }
}
