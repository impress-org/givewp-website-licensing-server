<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App/Models/License
 *
 * @method static License updateOrCreate
 */
class License extends Model
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
     * @param  string  $license_key  License Key.
     * @param  array  $data  Array of add-on information.
     *
     * @return License
     */
    public static function store(string $license_key, array $data): License
    {
        return self::updateOrCreate(
            ['license' => $license_key],
            ['data' => $data]
        );
    }
}
