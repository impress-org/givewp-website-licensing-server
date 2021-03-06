<?php
namespace Test\Unit\Models;

use App\Models\Addon;
use App\Repositories\Addons;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * Class TestAddon
 * @package Test\Modals
 */
class TestAddon extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var Addons $addon
     */
    private $addon;

    public function setUp(): void
    {
        parent::setUp();

        $this->addon = new Addons();
    }

    /**
     * @cover App\Modals\Addon::store
     */
    public function testStore()
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
        $this->assertEquals(['dummy data'], $output->data);
    }
}
