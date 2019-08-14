<?php

namespace Tests\Unit\Repositories;

use App\Models\License;
use App\Repositories\Licenses;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

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
        License::store($license_key, ['dummy data']);

        $output = $this->license->get($license_key);

        $this->assertInstanceOf(License::class, $output);
        $this->assertEquals($license_key, $output->license);
        $this->assertEquals(['dummy data'], $output->data);
    }

    /**
     * @cover \App\Repositories\Licenses::getAll
     */
    public function testShouldReturnNullWhenGetAllNonExistingLicense(): void
    {
        $license_keys = ['abc', 'def', 'ghi'];

        /* @var Collection|null $output */
        $output = $this->license->get($license_keys);

        $this->assertEquals(null, $output);
    }

    /**
     * @cover \App\Repositories\Licenses::getAll
     */
    public function testShouldReturnCollectionObjectWhenGetAllLicense(): void
    {
        $license_keys = ['abc', 'def', 'ghi'];

        License::store($license_keys[0], [$license_keys[0]]);
        License::store($license_keys[1], [$license_keys[1]]);
        License::store($license_keys[2], [$license_keys[2]]);


        $output = $this->license->getAll($license_keys);

        $this->assertInstanceOf(Collection::class, $output);
        $this->assertCount(count($license_keys), $output);

        foreach ($output as $item) {
            $this->assertContains($item->license, $license_keys);
            $this->assertEquals($item->data, [$item->license]);
        }
    }

    /**
     * @covers \App\Repositories\Licenses::delete
     */
    public function testShouldGetZeroWhenDeleteNonExistingLicense(): void
    {
        $result = app(Licenses::class)->delete('abc');

        $this->assertEquals(0, $result);
        $this->notSeeInDatabase('licenses', array( 'license' => 'abc'));
    }

    /**
     * @covers \App\Repositories\Licenses::delete
     */
    public function testShouldGetOneWhenDeleteLicense(): void
    {
        $license = License::store('abc', ['dummy data']);
        $result = app(Licenses::class)->delete('abc');

        $this->assertEquals($license->id, $result);
        $this->notSeeInDatabase('licenses', array( 'license' => 'abc'));
    }

    /**
     * @covers \App\Repositories\Licenses::deleteByAddon
     */
    public function testShouldGetZeroWhenDeleteNonExistingLicenseByAddon(): void
    {
        $result = app(Licenses::class)->deleteByAddon('xyz');

        $this->assertEquals(0, $result);
        $this->notSeeInDatabase('licenses', array( 'license' => 'xyz'));
    }

    /**
     * @covers \App\Repositories\Licenses::deleteByAddon
     */
    public function testShouldGetOneWhenDeleteLicenseByAddon(): void
    {
        $license = License::store('abc', ['get_version' => ['name'=>'xyx'] ]);
        $result = app(Licenses::class)->delete('abc');

        $this->assertEquals($license->id, $result);
        $this->notSeeInDatabase('licenses', array( 'license' => 'abc'));
    }
}
