<?php

namespace App\Repositories;

use App\Models\License;
use Illuminate\Support\Collection;

/**
 * Class Licenses
 *
 * Repository for handling licenses.
 *
 * @since   0.1.0
 *
 * @package App\Repositories
 */
class Licenses
{
    /**
     * Returns the option for the given key
     *
     * @param  string|array  $license_key  License key.
     *
     * @return License|Collection|null
     */
    public function get($license_key)
    {
        $licenses = null;

        // Exit.
        if (empty($license_key)) {
            return $licenses;
        }

        if (is_array($license_key)) {
            $licenses = License::whereIn('license', $license_key)->get();
        } else {
            $licenses = License::where('license', $license_key)->first();
        }

        return $licenses;
    }

    /**
     * Delete stored license data.
     *
     * @param  string  $license_key
     *
     * @return bool|int|null
     * @throws \Exception
     */
    public function delete(string $license_key)
    {
        return License::where('license', $license_key)->delete();
    }
}
