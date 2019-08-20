<?php


namespace Tests\Unit\Repositories;

use App\Repositories\SystemOptions;
use Illuminate\Database\QueryException;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class TestSystemOptions extends TestCase
{
    use DatabaseMigrations;

    /**
     * Checks to see that the repository is injected into the container
     */
    public function testContainer(): void
    {
        $this->assertInstanceOf(SystemOptions::class, app(SystemOptions::class));
    }

    /**
     * @covers \App\Repositories\SystemOptions::add
     */
    public function testAdd(): void
    {
        $repository = new SystemOptions();

        // Should successfully add option
        $repository->add('foo', '1', 'boolean');

        $this->seeInDatabase('system_options', [
            'key'   => 'foo',
            'value' => '1',
            'type'  => 'boolean'
        ]);

        // Should throw error that option already exists
        $this->expectException(QueryException::class);

        $repository->add('foo', 'fizzle');
    }

    /**
     * @covers \App\Repositories\SystemOptions::get
     */
    public function testGet(): void
    {
        $repository = new SystemOptions();

        // Should successfully get option
        $repository->add('foo', 'bar');

        $this->assertEquals('bar', $repository->get('foo'));

        // Should successfully get option model
        $repository->add('baz', 'test');

        $option = $repository->get('baz', true);

        $this->assertEquals('baz', $option->key);

        // Should return null for no key
        $this->assertNull($repository->get('non-existing-key'));
    }

    /**
     * @covers \App\Repositories\SystemOptions::update
     */
    public function testUpdate()
    {
        $repository = new SystemOptions();

        // Should create option when none exists
        $repository->update('foo', 'bar');

        $this->seeInDatabase('system_options', [
            'key'   => 'foo',
            'value' => 'bar',
        ]);

        // Should update the existing option
        $repository->update('foo', 'fizzle', 'boolean');

        $this->seeInDatabase('system_options', [
            'key'   => 'foo',
            'value' => 'fizzle',
            'type'  => 'boolean'
        ]);
    }

    /**
     * @covers \App\Repositories\SystemOptions::delete
     * @throws \Exception
     */
    public function testDelete(): void
    {
        $repository = new SystemOptions();

        // Should successfully delete option
        $repository->add('foo', 'bar', 'boolean');

        $repository->delete('foo');

        $this->notSeeInDatabase('system_options', ['key' => 'foo']);
    }
}
