<?php


namespace App\Upgrades;

use App\Contracts\Upgrades\VersionUpgrade;
use Illuminate\Console\Command;

/**
 * Class BaseUpgrade
 *
 * Base upgrade for all upgrades. Provides methods to be used within the upgrades.
 *
 * @package App\Upgrades
 */
abstract class BaseUpgrade implements VersionUpgrade
{
    /**
     * @since 0.1.0
     * @var Command
     */
    private $command;

    /**
     * VersionUpgrade constructor.
     *
     * @since 0.1.0
     *
     * @param Command $command The command running the upgrade
     */
    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    /**
     * @since 0.2.0
     *
     * @param array  $arguments
     * @param bool   $silent
     * @param string $command
     *
     * @return int
     */
    protected function callCommand(string $command, array $arguments = [], bool $silent = false): int
    {
        if ($silent) {
            return $this->command->callSilent($command, $arguments);
        } else {
            return $this->command->call($command, $arguments);
        }
    }
}
