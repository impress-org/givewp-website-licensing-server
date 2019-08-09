<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App/Models/License
 *
 * @method static Addon updateOrCreate
 */
class Addon extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['data', 'addon'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['data' => 'array'];

    /**
     * Store data
     *
     * @param  string  $addon_name  Add-on name.
     * @param  array  $data  Array of add-on information.
     *
     * @return Addon
     */
    public static function store(string $addon_name, array $data): Addon
    {
        return self::updateOrCreate(
            ['addon' => strtolower($addon_name)],
            ['data'  => $data]
        );
    }
}
