<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * App/Models/License
 *
 * @method static Builder|Subscription updateOrInsert
 * @method static Builder|Subscription where
 */
class Subscription extends Model
{
    protected $fillable = ['data', 'license'];

    /**
     * Store data
     *
     * @param $where
     * @param  array  $attributes
     *
     * @return bool
     */
    public static function store($where, $attributes = array())
    {
        $subscription = self::where($where)->first();

        // Where not any record found in the table then $subscription will set to zero which prevents insertion of the new record.
        // To prevent that we are initializing the model.
        $subscription = $subscription ?: new self();

        $subscription->fill(array_merge($where, $attributes));

        return $subscription->save();
    }
}
