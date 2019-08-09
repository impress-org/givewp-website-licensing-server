<?php

namespace Tests\Unit\Repositories;


use App\Repositories\Licenses;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class TestLicenses extends TestCase
{
    use DatabaseMigrations;

    /**
     * Checks to see that the repository is injected into the container
     */
    public function testContainer()
    {
        $this->assertInstanceOf(Licenses::class, app( Licenses::class));
    }
}
