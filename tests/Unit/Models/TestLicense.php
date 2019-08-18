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
     *  @covers \App\Models\License::store
     */
    public function testReturnLicenseModelWhenStore(): void
    {
        $license_key = 'abc';
        $data = [ 'license_key' => $license_key ];
        License::store($license_key, $data);

        $output = $this->license->get($license_key);

        $this->assertInstanceOf(License::class, $output);
        $this->assertEquals($license_key, $output->data['license_key']);
        $this->assertEquals($data, $output->data);
    }
}
