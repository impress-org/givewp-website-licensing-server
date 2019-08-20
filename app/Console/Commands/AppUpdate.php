<?php

namespace App\Console\Commands;

use App\Contracts\Upgrades\VersionUpgrade;
use App\Repositories\SystemOptions;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class AppUpdate
 *
 * Command that runs system updates
 *
 * @package App\Console\Commands
 */
class AppUpdate extends Command
{
    /**
     * {@inheritDoc}
     */
    protected $name = 'app:update';

    /**
     * {@inheritDoc}
     */
    protected $description = 'Checks if the application needs upgrade and triggers updates if so.';

    /**
     * {@inheritDoc}
     */
    protected function getOptions()
    {
        return [
            ['use-version', null, InputOption::VALUE_OPTIONAL, 'Version to act as instead of the database value']
        ];
    }

    /**
     * Create a new command instance.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @since 0.1.0
     *
     * @return mixed
     */
    public function handle()
    {
        // Use either the version stored or a version specified from the CLI
        if (null !== ($version = $this->option('use-version'))) {
            $startingVersion = $version;
        } else {
            $startingVersion = app(SystemOptions::class)->get('app_version') ?? '0.0.0';
        }

        $appVersion = config('app.version');

        if (version_compare($appVersion, $startingVersion, '>')) {
            $upgrades = config('app.upgrades');

            $upgrades = array_filter($upgrades, function ($version) use ($startingVersion) {
                return version_compare($startingVersion, $version, '<');
            }, ARRAY_FILTER_USE_KEY);

            if (empty($upgrades)) {
                $this->info('No upgrades need to be run.');
                $this->setAppVersion($appVersion);

                return 0;
            }

            // Sort by oldest to newest
            uksort($upgrades, 'version_compare');

            foreach ($upgrades as $version => $upgrade) {
                if (version_compare($appVersion, $version, '>=')) {
                    /** @var VersionUpgrade $upgrade */
                    $upgrade = app()->makeWith($upgrade, ['command' => $this]);

                    if ($version !== $upgrade->getVersion()) {
                        $this->error("Error: Version does not match for config version {$version} and "
                                     . get_class($upgrade));

                        return 1;
                    }

                    $this->info("Running upgrade for version {$version}");
                    $upgrade->runUpgrade();
                    $this->info("Upgrade complete for version {$version}");
                }
            }

            $this->setAppVersion($appVersion);

            $this->info("Update complete. Now running version $appVersion");

            return 0;
        } else {
            $this->info('The application is already at the latest version.');

            return 0;
        }
    }

    /**
     * Set the app version within the database.
     *
     * @since 0.1.0
     *
     * @param string $version
     */
    private function setAppVersion($version): void
    {
        app(SystemOptions::class)->update('app_version', $version);
    }
}
