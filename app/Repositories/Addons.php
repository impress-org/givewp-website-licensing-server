<?php

namespace App\Repositories;

use App\Models\Addon;

/**
 * Class Add-ons
 *
 * Repository for handling add-ons.
 *
 * @since   0.1.0
 *
 * @package App\Repositories
 */
class Addons
{
    /**
     * Returns the option for the given key
     *
     * @param  string  $addon  Add-on name.
     *
     * @return Addon|null
     */
    public function get(string $addon)
    {
        $addons = null;

        // Exit.
        if (empty($addon)) {
            return $addons;
        }

        $addons = Addon::whereRaw("UPPER(addon) LIKE '%{$addon}%'")
                       ->select('addon', 'data')
                       ->first();

        return $addons;
    }
}
