<?php

namespace Tests\Unit\Commands;

use App\Repositories\SystemOptions;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * Class TestAppVersion
 * @package Tests\Unit\Commands
 * @coversDefaultClass \App\Console\Commands\AppVersion
 */
class TestAppVersion extends TestCase
{
    use DatabaseMigrations;

    /**
     * @since 0.1.0
     */
    public function testCommand(): void
    {
        // Set versions
        app(SystemOptions::class)->update('app_version', '0.9.0');
        Config::set('app.version', '1.0.0');

        // Should reflect the versions
        Artisan::Call('app:version');

        $output = Artisan::output();
        $this->assertContains('App Version: 1.0.0', $output);
        $this->assertContains('Database Version: 0.9.0', $output);

        // Should display *only* the app version
        Artisan::Call('app:version --plain');

        $output = Artisan::output();
        $this->assertContains('1.0.0', $output);
    }
}
