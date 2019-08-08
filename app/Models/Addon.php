<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * App/Models/License
 *
 * @method static Model|Builder|Addon updateOrInsert
 * @method static Model|Builder|Addon whereRaw
 * @method static Model|Builder|Addon where
 */
class Addon extends Model
{
    protected $fillable = ['data', 'addon'];

    /**
     * Store data
     *
     * @param  string  $addon  Add-on name.
     * @param  array  $data  Array of add-on information.
     *
     * @return bool
     */
    public static function store(string $addon, array $data)
    {
        $addon = self::where('addon', $addon)->first();

        // Where not any record found in the table then $addon will set to zero which prevents insertion of the new record.
        // To prevent that we are initializing the model.
        $addon = $addon ?: new self();

        $data['addon'] = $addon;

        $addon->fill($data);

        return $addon->save();
    }
}
