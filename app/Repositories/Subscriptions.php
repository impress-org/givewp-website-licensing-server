<?php

namespace App\Repositories;

use App\Models\Subscription;
use Exception;
use Illuminate\Database\Eloquent\Builder;

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
     * @return Subscription|Builder|null
     */
    public function get($license_key): ?Subscription
    {
        $subscription = null;

        // Exit.
        if (empty($license_key)) {
            return $subscription;
        }

        return Subscription::where('license', $license_key)->first();
    }

    /**
     * Delete stored subscription data.
     *
     * @param  string  $license_key
     *
     * @return bool|mixed|null
     * @throws Exception
     */
    public function delete(string $license_key)
    {
        return Subscription::where('license', $license_key)->delete();
    }

    /**
     * Delete stored subscription data.
     *
     * @param  int  $subscriptionID
     *
     * @return bool|mixed|null
     * @throws Exception
     */
    public function deleteBySubscriptionID(int $subscriptionID)
    {
        return Subscription::where('subscription', $subscriptionID)->delete();
    }
}
