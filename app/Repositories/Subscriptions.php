<?php

namespace App\Repositories;

use App\Models\Subscription;

/**
 * Class Subscriptions
 *
 * Repository for handling subscriptions.
 *
 * @since   0.1.0
 *
 * @package App\Repositories
 */
class Subscriptions
{
    /**
     * Returns the option for the given key
     *
     * @param  string|array  $license_key  License key.
     *
     * @return Subscription|null
     */
    public function get($license_key)
    {
        $subscription = null;

        // Exit.
        if (empty($license_key)) {
            return $subscription;
        }


        $subscription = Subscription::where('license', $license_key)
                                ->select('license', 'data')
                                ->first();

        return $subscription;
    }
}
