<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App/Models/License
 *
 * @method static Subscription updateOrCreate
 * @method static Builder|License|null where
 */
class Subscription extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['data', 'license', 'subscription'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['data' => 'array'];

    /**
     * Store data
     *
     * @param  string  $license_key  License Key.
     * @param  array  $data  Array of subscription data.
     *
     * @return Subscription
     */
    public static function store(string $license_key, array $data): Subscription
    {
        return self::updateOrCreate(
            ['license' => $license_key],
            [
                'data' => $data,
                'subscription' => $data['id']
            ]
        );
    }
}
