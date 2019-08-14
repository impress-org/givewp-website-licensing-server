<?php

namespace App\Repositories;

use App\Models\Addon;
use Illuminate\Database\Eloquent\Builder;

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
     * @return Addon|Builder|null
     */
    public function get(string $addon)
    {
        $addons = null;

        // Exit.
        if (empty($addon)) {
            return $addons;
        }

        return Addon::where(
            'addon',
            'like',
            '%' . strtolower($addon) . '%'
        )->first();
    }

    /**
     * Delete add-on
     *
     * @param $addon
     *
     * @return bool|mixed|null
     * @throws \Exception
     */
    public function delete($addon)
    {
        return Addon::where(
            'addon',
            'like',
            '%' . strtolower($addon) . '%'
        )->delete();
    }
}
