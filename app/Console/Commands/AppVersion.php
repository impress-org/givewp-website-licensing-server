<?php

namespace App\Console\Commands;

use App\Repositories\SystemOptions;
use Illuminate\Console\Command;

class AppVersion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:version {--plain : Outputs only the app version with no new line}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Displays the current app and database versions';

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
        if ($this->option('plain')) {
            $this->output->write(config('app.version'));
        } else {
            $this->info('App Version: ' . config('app.version'));
            $this->info('Database Version: ' . app(SystemOptions::class)->get('app_version'));
        }
    }
}
