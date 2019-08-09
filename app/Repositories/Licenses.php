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
            $licenses = License::whereIn('license', $license_key)
                               ->select('license', 'data')
                               ->get();
        } else {
            $licenses = License::where('license', $license_key)
                               ->select('license', 'data')
                               ->first();
        }

        return $licenses;
    }
}
