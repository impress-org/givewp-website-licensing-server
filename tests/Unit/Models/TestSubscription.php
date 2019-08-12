<?php

namespace Tests\Unit\Subscription;

use App\Models\Subscription;
use App\Repositories\Subscriptions;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class TestSubscription extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var Subscriptions $license
     * @access private
     */
    private $license;

    function setUp(): void
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
        Subscription::store($license_key, ['dummy data']);

        $output = $this->license->get($license_key);

        $this->assertInstanceOf(Subscription::class, $output);
        $this->assertEquals($license_key, $output->license);
        $this->assertEquals(array('dummy data'), $output->data);
    }
}
