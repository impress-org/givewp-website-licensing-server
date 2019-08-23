<?php

namespace Tests\Unit\Commands;

use App\Contracts\Upgrades\VersionUpgrade;
use App\Repositories\SystemOptions;
use Illuminate\Support\Facades\Config;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Mockery;
use Tests\TestCase;

/**
 * Class TestAppUpdate
 * @package Tests\Unit\Commands
 * @coversDefaultClass \App\Console\Commands\AppUpdate
 */
class TestAppUpdate extends TestCase
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
    use DatabaseMigrations;

    /**
     * Sets up and runs the common unit test criteria.
     *
     * @since 0.1.0
     *
     * @param string $version
     * @param bool   $shouldRunUpgrade
     */
    private function runUpgradeTest(string $version, bool $shouldRunUpgrade): void
    {
        // Set up mock upgrade
        /** @var Mockery\MockInterface $upgrade */
        $upgrade = Mockery::spy(VersionUpgrade::class);
        $this->app->bind(
            VersionUpgrade::class,
            function () use ($upgrade) {
                return $upgrade;
            },
            true
        );

        $upgrade
            ->allows()
            ->getVersion()
            ->andReturns($version);
        $upgrade->allows()->runUpgrade();

        // Set up mock options
        $options = Mockery::spy(SystemOptions::class);
        $options
            ->allows()
            ->get('app_version')
            ->andReturns('1.0.0');
        $options->allows()->update('app_version', '1.1.0');
        $this->app->instance(SystemOptions::class, $options);

        // Set mock config values
        Config::set('app.version', '1.1.0');
        Config::set('app.upgrades', [$version => VersionUpgrade::class]);

        // Should run the upgrade
        $this->assertEquals(0, $this->artisan('app:update'));

        $options->shouldHaveReceived('update');

        if ($shouldRunUpgrade) {
            $upgrade->shouldHaveReceived('runUpgrade');
        } else {
            $upgrade->shouldNotHaveReceived('runUpgrade');
        }
    }

    /**
     * @since 0.1.0
     */
    public function testShouldRunCurrentUpgrade()
    {
        $this->runUpgradeTest('1.1.0', true);
    }

    /**
     * @since 0.1.0
     */
    public function testShouldNotRunPastUpgrade()
    {
        $this->runUpgradeTest('0.9.0', false);
    }

    /**
     * @since 0.1.0
     */
    public function testShouldNotRunFutureUpgrade()
    {
        $this->runUpgradeTest('1.2.0', false);
    }

    /**
     * @since 0.3.0 now expects all updates to run without a version
     * @since 0.1.0
     */
    public function testShouldRunAllUpdatesWithoutDatabaseVersion()
    {
        // Set up mock options
        $options = Mockery::spy(SystemOptions::class);
        $options
            ->allows()
            ->get('app_version')
            ->andReturns(null);
        $this->app->instance(SystemOptions::class, $options);

        // Should exit due to no database version
        $this->assertEquals(0, $this->artisan('app:update'));

        $options->shouldHaveReceived('update');
    }

    /**
     * @since 0.1.0
     */
    public function testShouldCheckUpgradeVersionMatch()
    {
        // Set up mock upgrade
        /** @var Mockery\MockInterface $upgrade */
        $upgrade = Mockery::spy(VersionUpgrade::class);
        $upgrade
            ->allows()
            ->getVersion()
            ->andReturns('1.1.1');
        $this->app->bind(
            VersionUpgrade::class,
            function () use ($upgrade) {
                return $upgrade;
            },
            true
        );

        // Set up mock options
        $options = Mockery::spy(SystemOptions::class);
        $options
            ->allows()
            ->get('app_version')
            ->andReturns('1.0.0');
        $this->app->instance(SystemOptions::class, $options);

        // Set mock config values
        Config::set('app.version', '1.1.0');
        Config::set('app.upgrades', ['1.1.0' => VersionUpgrade::class]);

        // Should exit with code 2 as upgrade version does not match
        $this->assertEquals(1, $this->artisan('app:update'));

        $options->shouldNotHaveReceived('update');
    }
}
