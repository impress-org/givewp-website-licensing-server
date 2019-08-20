<?php

namespace App\Contracts\Upgrades;

interface VersionUpgrade
{

    /**
     * Return the version the upgrade is intended for.
     *
     * @since 0.1.0
     *
     * @return string
     */
    public function getVersion(): string;

    /**
     * Run the version upgrade.
     */
    public function runUpgrade(): void;
}
