<?php

namespace Tests\Unit\License;

use App\Models\License;
use App\Repositories\Licenses;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;
use function App\Helpers\getLicenseIdentifier;
use function Tests\Helpers\getLicenseData;

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
        $license_data = getLicenseData(['license_key'=> 'abc']);
        License::store($license_data['check_license']['license_key'], $license_data);

        $output = $this->license->get($license_data['check_license']['license_key']);
        $key = getLicenseIdentifier($license_data['check_license']['license_key']);

        $this->assertInstanceOf(License::class, $output);
        $this->assertEquals($license_data['check_license']['license_key'], $output->data['check_license']['license_key']);
        $this->assertEquals($license_data, $output->data);
        $this->assertEquals($key, $output->key);
    }
}
