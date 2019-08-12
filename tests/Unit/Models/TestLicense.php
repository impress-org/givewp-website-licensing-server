<?php

namespace Tests\Unit\License;

use App\Models\License;
use App\Repositories\Licenses;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class TestLicense extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var Licenses $license
     * @access private
     */
    private $license;

    public function setUp(): void
    {
        parent::setUp();

        $this->license = new Licenses();
    }

    /**
     * @covers \App\Models\License::store
     */
    public function testStore()
    {
        $license_key = 'abc';

        /**
         * Case: if license does not exist
         */
        /* @var License|null $output */
        $output = $this->license->get($license_key);

        $this->assertEquals(null, $output);

        /**
         * Case: If license exist
         */
        License::store($license_key, ['dummy data']);

        $output = $this->license->get($license_key);

        $this->assertInstanceOf(License::class, $output);
        $this->assertEquals($license_key, $output->license);
        $this->assertEquals(['dummy data'], $output->data);
    }
}
