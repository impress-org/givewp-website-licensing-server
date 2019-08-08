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
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['data', 'license'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['data' => 'array'];

    /**
     * Store data
     *
     * @param  string  $license  License Key.
     * @param  array  $data  Array of subscription data.
     *
     * @return bool
     */
    public static function store(string $license, array $data)
    {
        $subscription = self::where('license', $license)->first();

        // Where not any record found in the table then $subscription will set to zero which prevents insertion of the new record.
        // To prevent that we are initializing the model.
        $subscription = $subscription ?: new self();

        $subscription->fill([
            'data'    => $data,
            'license' => $license,
        ]);

        return $subscription->save();
    }
}
