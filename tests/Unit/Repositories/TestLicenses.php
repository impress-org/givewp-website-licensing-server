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

    function setUp(): void
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
     * @covers \App\Repositories\Licenses::get
     */
    public function testGet()
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
        $this->assertEquals(array('dummy data'), $output->data);
    }

    /**
     * @covers \App\Repositories\Licenses::getAll
     */
    public function testGetAll()
    {
        $license_keys = ['abc', 'def', 'ghi'];

        /**
         * Case: if licenses do not exist
         */
        /* @var Collection|null $output */
        $output = $this->license->get($license_keys);

        $this->assertEquals(null, $output);

        /**
         * Case: If licenses exist
         */
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
     * @throws \Exception
     */
    function testDelete()
    {
        $license_key = 'abc';

        /**
         * Case: if license does not exist
         */
        /* @var License|null $output */
        $output = $this->license->delete($license_key);

        $this->assertIsInt($output);
        $this->assertEquals(0, $output);

        /**
         * Case: If license exist
         */
        $license = License::store($license_key, ['dummy data']);

        $output = $this->license->delete($license_key);

        $this->assertIsInt($output);
        $this->assertEquals($license->id, $output);
    }
}
