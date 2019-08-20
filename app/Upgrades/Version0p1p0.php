<?php

namespace App\Upgrades;

class Version0p1p0 extends BaseUpgrade
{
    private const VERSION = '0.1.0';

    /**
     * {@inheritDoc}
     */
    public function getVersion(): string
    {
        return self::VERSION;
    }

    /**
     * {@inheritDoc}
     */
    public function runUpgrade(): void
    {
        $this->callCommand('migrate');
    }
}
