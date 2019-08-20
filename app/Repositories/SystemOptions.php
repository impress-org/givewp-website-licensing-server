<?php

namespace App\Repositories;

use App\Models\SystemOption;
use Exception;
use Illuminate\Database\Eloquent\Concerns\HasAttributes;

/**
 * Class SystemOptions
 *
 * Repository for handling system options. Options may have a type specified which casts the stored value to the type
 * when it's retrieved. For a list of supported types view the castAttribute method below.
 *
 * @since   0.1.0
 *
 * @see     HasAttributes::castAttribute()
 * @package App\Repositories
 */
class SystemOptions
{
    /**
     * Returns the option for the given key
     *
     * @since 0.1.0
     *
     * @param bool   $returnModel Whether to return the model or value
     * @param string $key
     *
     * @return SystemOption|mixed|null
     *
     */
    public function get(string $key, bool $returnModel = false)
    {
        $option = SystemOption::query()->where('key', $key)->first();

        if (null === $option) {
            return null;
        }

        if ($returnModel) {
            return $option;
        }

        return $option->value;
    }

    /**
     * Adds the option, throwing an error if option already exists, then returns the new option
     *
     * @since 0.1.0
     *
     * @param mixed       $value
     * @param string|null $type
     * @param bool        $autoload
     *
     * @param string      $key
     *
     * @return SystemOption
     *
     */
    public function add(string $key, $value, ?string $type = null, bool $autoload = false): SystemOption
    {
        return SystemOption::create([
            'key'      => $key,
            'value'    => $value,
            'type'     => $type,
            'autoload' => $autoload
        ]);
    }

    /**
     * Updates or creates the option, then returns the new/updated option
     *
     * @since 0.1.0
     *
     * @param mixed       $value
     * @param string|null $type
     * @param bool        $autoload
     * @param string      $key
     *
     * @return SystemOption
     */
    public function update(string $key, $value, ?string $type = null, bool $autoload = null): SystemOption
    {
        $option = [
            'key'   => $key,
            'value' => $value,
            'type'  => $type,
        ];

        if (null !== $autoload) {
            $option['autoload'] = $autoload;
        }

        return SystemOption::updateOrCreate(['key' => $key], $option);
    }

    /**
     * Deletes the option
     *
     * @since 0.1.0
     *
     * @param string $key
     *
     * @throws Exception
     */
    public function delete(string $key): void
    {
        $option = $this->get($key, true);

        if ($option) {
            $option->delete();
        }
    }
}
