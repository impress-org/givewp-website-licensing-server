<?php
declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class AppFresh
 *
 * This command starts the application over from a fresh state.
 *
 * @package App\Console\Commands
 */
class AppFresh extends Command
{
    /**
     * {@inheritDoc}
     */
    protected $name = 'app:fresh';

    /**
     * {@inheritDoc}
     */
    protected $description = 'Completely flushes and refreshes the app. Use with care.';

    /**
     * {@inheritDoc}
     */
    protected function getOptions()
    {
        return [
            ['yes', 'y', InputOption::VALUE_NONE, 'Skips the confirmation check']
        ];
    }

    /**
     * AppFresh constructor.
     *
     * @since 0.3.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @since 0.3.0
     *
     * @return mixed
     */
    public function handle()
    {
        if (App::environment('production')) {
            $this->error('This command cannot be run on production.');

            return 1;
        }

        // phpcs:disable Generic.Files.LineLength
        if (! $this->option('yes') && ! $this->confirm('This will completely wipe all data and cannot be restored. Do you want to continue?')) {
            return 0;
        }

        $this->info('Rebuilding database...');

        if ($this->call('migrate:fresh')) {
            $this->error('Failed to rebuild the database.');

            return 2;
        }

        $this->info('Seeding database...');
        if ($this->call('db:seed')) {
            $this->error('Failed to seed the database');

            return 3;
        }

        $this->info('Running updates...');
        if ($this->call('app:update')) {
            $this->error('Failed to run application updates');

            return 4;
        }

        return 0;
    }
}
