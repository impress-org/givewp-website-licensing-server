<?php

namespace Tests\Unit\Repositories;

use App\Models\License;
use App\Repositories\Licenses;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;
use function App\Helpers\getLicenseIdentifier;
use function Tests\Helpers\getLicenseData;

class TestLicenses extends TestCase
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
     * Checks to see that the repository is injected into the container
     */
    public function testContainer()
    {
        $this->assertInstanceOf(Licenses::class, app(Licenses::class));
    }

    /**
     * @cover \App\Repositories\Licenses::get
     */
    public function testShouldReturnNullWhenGetNonExistingLicense(): void
    {
        $license_key = 'abc';
        $output = $this->license->get($license_key);

        $this->assertEquals(null, $output);
    }

    /**
     * @cover \App\Repositories\Licenses::get
     */
    public function testShouldReturnLicenseModelWhenGetLicense(): void
    {
        $license_data = getLicenseData(['license_key' => 'abc']);
        License::store($license_data['check_license']['license_key'], $license_data);

        $output = $this->license->get($license_data['check_license']['license_key']);

        $this->assertInstanceOf(License::class, $output);
        $this->assertEquals($license_data['check_license']['license_key'], $output->license);
        $this->assertEquals($license_data, $output->data);
        $this->assertEquals('valid', $output->data['check_license']['license']);
    }

    /**
     * @cover \App\Repositories\Licenses::getAll
     */
    public function testShouldReturnEmptyCollectionWhenGetAllNonExistingLicense(): void
    {
        $license_keys = ['abc', 'def', 'ghi'];

        /* @var Collection|null $output */
        $output = $this->license->getAll($license_keys);

        $this->assertTrue($output->isEmpty());
    }

    /**
     * @cover \App\Repositories\Licenses::getAll
     */
    public function testShouldReturnCollectionObjectWhenGetAllLicense(): void
    {
        $license_keys = ['abc', 'def', 'ghi'];
        $license_datas = [
            getLicenseData(['license_key' => $license_keys[0] ]),
            getLicenseData(['license_key' => $license_keys[1] ]),
            getLicenseData(['license_key' => $license_keys[2] ])
        ];

        License::store($license_datas[0]['check_license']['license_key'], $license_datas[0]);
        License::store($license_datas[1]['check_license']['license_key'], $license_datas[1]);
        License::store($license_datas[2]['check_license']['license_key'], $license_datas[2]);


        $output = $this->license->getAll($license_keys);

        $this->assertInstanceOf(Collection::class, $output);
        $this->assertCount(count($license_keys), $output);

        foreach ($output as $item) {
            $arrayIndex = array_search($item->license, $license_keys, true);
            $this->assertContains($item->license, $license_keys);
            $this->assertEquals($item->data, $license_datas[$arrayIndex]);
        }
    }

    /**
     * @covers \App\Repositories\Licenses::delete
     */
    public function testShouldGetZeroWhenDeleteNonExistingLicense(): void
    {
        $result = app(Licenses::class)->delete('abc');

        $this->assertEquals(0, $result);
    }

    /**
     * @covers \App\Repositories\Licenses::delete
     */
    public function testShouldGetOneWhenDeleteLicense(): void
    {
        $license_data = getLicenseData(['license_key' => 'abc' ]);
        $license = License::store($license_data['check_license']['license_key'], $license_data);
        $result = app(Licenses::class)->delete('abc');

        $key = getLicenseIdentifier($license_data['check_license']['license_key']);

        $this->assertEquals($license->id, $result);
        $this->notSeeInDatabase('licenses', array( 'key' => $key ));
    }

    /**
     * @covers \App\Repositories\Licenses::deleteByAddon
     */
    public function testShouldGetZeroWhenDeleteNonExistingLicenseByAddon(): void
    {
        $addon_name = 'xyz';
        $result = app(Licenses::class)->deleteByAddon($addon_name);

        $this->assertEquals(0, $result);
    }

    /**
     * @covers \App\Repositories\Licenses::deleteByAddon
     */
    public function testShouldGetOneWhenDeleteLicenseByAddon(): void
    {
        $license_data = getLicenseData(['license_key' => 'abc', 'item_name' => 'xyz']);
        $license = License::store($license_data['check_license']['license_key'], $license_data);
        $result = app(Licenses::class)->deleteByAddon($license_data['check_license']['item_name']);

        $key = getLicenseIdentifier($license_data['check_license']['license_key']);

        $this->assertEquals($license->id, $result);
        $this->notSeeInDatabase('licenses', array( 'key' => $key ));
    }

    /**
     * @covers \App\Repositories\Licenses::deleteAll
     */
    public function testShouldGetZeroWhenDeleteNonExistingMultipleLicenses(): void
    {
        $result = app(Licenses::class)->deleteAll(['abc', 'def', 'ghi']);

        $this->assertEquals(0, $result);
    }

    /**
     * @covers \App\Repositories\Licenses::deleteAll
     */
    public function testShouldGetOneWhenDeleteMultipleLicenses(): void
    {
        $abc_license_data = getLicenseData(['license_key' => 'abc' ]);
        $def_license_data = getLicenseData(['license_key' => 'def' ]);
        $ghi_license_data = getLicenseData(['license_key' => 'ghi' ]);

        $abc_license = License::store($abc_license_data['check_license']['license_key'], $abc_license_data);
        $def_license = License::store($def_license_data['check_license']['license_key'], $def_license_data);
        $ghi_license = License::store($ghi_license_data['check_license']['license_key'], $ghi_license_data);

        $result = app(Licenses::class)->deleteAll([$abc_license_data['check_license']['license_key'], $def_license_data['check_license']['license_key'], $ghi_license_data['check_license']['license_key']]);

        $abc_key = getLicenseIdentifier($abc_license_data['check_license']['license_key']);
        $def_key = getLicenseIdentifier($def_license_data['check_license']['license_key']);
        $ghi_key = getLicenseIdentifier($ghi_license_data['check_license']['license_key']);

        $this->assertEquals(3, $result);

        $this->notSeeInDatabase('licenses', array( 'key' => $abc_key ));
        $this->notSeeInDatabase('licenses', array( 'key' => $def_key ));
        $this->notSeeInDatabase('licenses', array( 'key' => $ghi_key ));
    }

    /**
     * @cover \App\Repositories\Licenses::get
     */
    public function testShouldReturnLicenseModelWithExpiredStatusWhenGetSingleLicense(): void
    {
        $license_data = getLicenseData(['license_key' => 'abc', 'expires' => date( 'Y-m-d H:i:s', strtotime('-2 year'))]);
        License::store($license_data['check_license']['license_key'], $license_data);

        $output = $this->license->get($license_data['check_license']['license_key']);

        $this->assertEquals('expired', $output->data['check_license']['license']);
    }

    /**
     * @cover \App\Repositories\Licenses::getAll
     */
    public function testShouldReturnLicenseModelWithExpiredStatusWhenGetMultipleLicenses(): void
    {
        $abc_license_data = getLicenseData(['license_key' => 'abc' ]);
        $def_license_data = getLicenseData(['license_key' => 'def', 'expires' =>  date( 'Y-m-d H:i:s', strtotime('-2 year'))]);
        $ghi_license_data = getLicenseData(['license_key' => 'ghi', 'expires' =>  date( 'Y-m-d H:i:s', strtotime('-2 year'))]);

        License::store($abc_license_data['check_license']['license_key'], $abc_license_data);
        License::store($def_license_data['check_license']['license_key'], $def_license_data);
        License::store($ghi_license_data['check_license']['license_key'], $ghi_license_data);

        $abc_output = $this->license->get($abc_license_data['check_license']['license_key']);
        $def_output = $this->license->get($def_license_data['check_license']['license_key']);
        $ghi_output = $this->license->get($ghi_license_data['check_license']['license_key']);


        $this->assertEquals('valid', $abc_output->data['check_license']['license']);
        $this->assertEquals('expired', $def_output->data['check_license']['license']);
        $this->assertEquals('expired', $ghi_output->data['check_license']['license']);
    }
}
