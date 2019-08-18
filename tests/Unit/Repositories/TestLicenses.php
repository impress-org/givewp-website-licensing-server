<?php

namespace Tests\Unit\Repositories;

use App\Models\License;
use App\Repositories\Licenses;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;
use function App\Helpers\getLicenseIdentifier;

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
    public function testShouldReturnLicenseModelWhenGetNonExistingLicense(): void
    {
        $license_key = 'abc';
        $data = [ 'license_key' => $license_key ];
        License::store($license_key, $data);

        $output = $this->license->get($license_key);

        $this->assertInstanceOf(License::class, $output);
        $this->assertEquals($license_key, $output->data['license_key']);
        $this->assertEquals($data, $output->data);
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

        License::store($license_keys[0], ['license_key' => $license_keys[0]]);
        License::store($license_keys[1], ['license_key' => $license_keys[1]]);
        License::store($license_keys[2], ['license_key' => $license_keys[2]]);


        $output = $this->license->getAll($license_keys);

        $this->assertInstanceOf(Collection::class, $output);
        $this->assertCount(count($license_keys), $output);

        foreach ($output as $item) {
            $this->assertContains($item->data['license_key'], $license_keys);
            $this->assertEquals($item->data, [ 'license_key' => $item->data['license_key']]);
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
        $license_key = 'abc';
        $license = License::store($license_key, [ 'license_key' => $license_key ]);
        $result = app(Licenses::class)->delete('abc');

        $key = getLicenseIdentifier($license_key);

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
        $license_key = 'abc';
        $addon_name = 'xyx';
        $license = License::store($license_key, ['get_version' => ['name'=>$addon_name] ]);
        $result = app(Licenses::class)->deleteByAddon($addon_name);

        $key = getLicenseIdentifier($license_key);

        $this->assertEquals($license->id, $result);
        $this->notSeeInDatabase('licenses', array( 'key' => $key ));
    }
}
