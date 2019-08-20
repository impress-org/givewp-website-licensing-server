<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SystemOption
 *
 * @since 0.1.0
 *
 * @method static Builder|SystemOption create($attributes)
 * @method static Builder|SystemOption updateOrCreate($attributes, $values)
 * @method static Builder|SystemOption whereAutoload($value)
 * @method static Builder|SystemOption whereId($value)
 * @method static Builder|SystemOption whereKey($value)
 * @method static Builder|SystemOption whereType($value)
 * @method static Builder|SystemOption whereValue($value)
 * @method static Builder|SystemOption newModelQuery()
 * @method static Builder|SystemOption newQuery()
 * @method static Builder|SystemOption query()
 * @property int     $id
 * @property string  $key
 * @property mixed   $value
 * @property string  $type
 * @property boolean $autoload
 */
class SystemOption extends Model
{
    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'key',
        'value',
        'type',
        'autoload'
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'autoload' => 'boolean'
    ];

    /**
     * {@inheritdoc}
     */
    public $timestamps = false;

    /**
     * Set the casts property to the type value for dynamic type casting.
     *
     * @since 0.1.0
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        if (! empty($attributes['type'])) {
            $this->casts['value'] = $attributes['type'];
        }

        parent::__construct($attributes);
    }
}
