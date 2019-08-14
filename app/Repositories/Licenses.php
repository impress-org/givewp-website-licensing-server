<?php

namespace App\Repositories;

use App\Models\License;
use Exception;
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
     * Returns the license for the given key
     *
     * @param  string|array  $license_key  License key.
     *
     * @return License|null
     */
    public function get($license_key): ?License
    {
        return License::where('license', $license_key)->first();
    }

    /**
     * Returns the licenses for the given keys
     *
     * @param $license_keys
     *
     * @return Collection|null
     */
    public function getAll(array $license_keys): ?Collection
    {
        return License::whereIn('license', $license_keys)->get();
    }

    /**
     * Delete stored license data.
     *
     * @param  string  $license_key
     *
     * @return bool|mixed|null
     * @throws Exception
     */
    public function delete(string $license_key)
    {
        return License::where('license', $license_key)->delete();
    }

    /**
     * Delete license by add-on
     *
     * @param $addon
     *
     * @return bool|mixed|null
     * @throws Exception
     */
    public function deleteByAddon($addon)
    {
        return License::where(
            'data',
            'like',
            '%' . strtolower($addon) . '%'
        )->delete();
    }
}
