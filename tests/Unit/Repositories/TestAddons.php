<?php

namespace Test\Repositories;

use App\Models\Addon;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Repositories\Addons;

/**
 * Class TestAddons
 * @package Test\Repositories
 */
class TestAddons extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var Addons $addon
     * @access private
     */
    private $addon;

    function setUp(): void
    {
        parent::setUp();

        $this->addon = new Addons();
    }

    /**
     * Checks to see that the repository is injected into the container
     */
    public function testContainer()
    {
        $this->assertInstanceOf(Addons::class, app(Addons::class));
    }

    /**
     * @cover \App\Repositories\Addons::get
     */
    public function testGet()
    {
        $addon_name = 'abc';

        /**
         * Case: if license does not exist
         */
        /* @var Addons|null $output */
        $output = $this->addon->get($addon_name);

        $this->assertEquals(null, $output);

        /**
         * Case: If license exist
         */
        Addon::store($addon_name, ['dummy data']);

        $output = $this->addon->get($addon_name);

        $this->assertInstanceOf(Addon::class, $output);
        $this->assertEquals($addon_name, $output->addon);
        $this->assertEquals(array('dummy data'), $output->data);
    }
}