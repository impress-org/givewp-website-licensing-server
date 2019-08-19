<?php

namespace App\Repositories;

use App\Models\License;
use Exception;
use Illuminate\Support\Collection;
use function App\Helpers\getLicenseIdentifier;

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
     * @param  string  $license_key  License key.
     *
     * @return License|null
     */
    public function get($license_key): ?License
    {
        $key = getLicenseIdentifier($license_key);
        return License::where('key', $key)->first();
    }

    /**
     * Returns the licenses for the given keys
     *
     * @param array $license_keys
     *
     * @return Collection|null
     */
    public function getAll(array $license_keys): ?Collection
    {
        $keys = [];
        foreach ($license_keys as $license_key) {
            $keys[] = getLicenseIdentifier($license_key);
        }
        return License::whereIn('key', $keys)->get();
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
     * Delete all stored license data.
     *
     * @param  array $license_keys
     *
     * @return bool|mixed|null
     * @throws Exception
     */
    public function deleteAll(array $license_keys)
    {
        return License::whereIn('license', $license_keys)->delete();
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
        return License::where('addon', strtolower($addon))->delete();
    }
}
