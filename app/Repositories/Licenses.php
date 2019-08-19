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
        $license = License::where('key', $key)->first();
        $license = $this->setExpiredStatus($license);

        return $license;
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

        $licenses = License::whereIn('key', $keys)->get();

        if ($licenses->isNotEmpty()) {
            foreach ($licenses as $key => $license) {
                $licenses[$key] = $this->setExpiredStatus($license);
            }
        }

        return $licenses;
    }

    /**
     * Delete stored license data.
     *
     * @param  string  $license_key Number of row deleted
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
     * @return bool|mixed|null Number of row deleted
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

    /**
     * Verify and set expired status of license
     *
     * @param $license
     *
     * @return License|Collection|null
     */
    private function setExpiredStatus($license)
    {
        if (! $license) {
            return $license;
        }

        // Check if license expires or not.
        if ($license
            && $license->data['license'] !== 'expired'
            && strtotime($license->data['expires']) <= time()
        ) {
            $tmp = $license->data;
            $tmp['license'] = 'expired';

            $license->data = $tmp;
        }

        return $license;
    }
}
