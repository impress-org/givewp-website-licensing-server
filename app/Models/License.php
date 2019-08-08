<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * App/Models/License
 *
 * @method static Model|Builder|License updateOrInsert
 * @method static Model|Builder|License where
 * @method static Model|Builder|License whereIn
 */
class License extends Model
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
        $license = self::where($where)->first();

        // Where not any record found in the table then $license will set to zero which prevents insertion of the new record.
        // To prevent that we are initializing the model.
        $license = $license ?: new self();

        $license->fill(array_merge($where, $attributes));

        return $license->save();
    }
}
