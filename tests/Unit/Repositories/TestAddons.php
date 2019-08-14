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

    public function setUp(): void
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
        $this->assertEquals(['dummy data'], $output->data);
    }


    /**
     * @covers \App\Repositories\Addons::delete
     */
    public function testShouldGetZeroWhenDeleteNonExistingAddon(): void
    {
        $result = app(Addons::class)->delete('abc');

        $this->assertEquals(0, $result);
        $this->notSeeInDatabase('addons', array( 'addon' => 'abc'));
    }

    /**
     * @covers \App\Repositories\Addons::delete
     */
    public function testShouldGetBoolWhenDeleteAddon(): void
    {
        $addon = Addon::store('abc', ['dummy data']);
        $result = app(Addons::class)->delete('abc');

        $this->assertEquals($addon->id, $result);
        $this->notSeeInDatabase('addons', array( 'addon' => 'abc'));
    }
}
