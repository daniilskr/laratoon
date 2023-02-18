<?php

namespace App\Models\Concerns;

use Exception;
use Illuminate\Support\Str;
use LogicException;

/**
 * You have to define $slugSource property - a column name or an
 * array of column names which are used to compute the slug.
 *
 * protected array|string $slugSource = ['title', 'number'];
 * => 'pepper-and-carrot-12'
 *
 *
 * Slug is auto-computed on the 'creating' model event.
 * Define "protected $doNotUpdateSlugWhenCreating = true"
 * on a model to prevent this behavior if you are gonna
 * use some column like 'id' that is not available at the
 * 'creating' time.
 *
 *
 * You can use string literals along with column names,
 * just put dashes around string literals to achieve this
 *
 * protected array|string $slugSource = ['title', '-by-', 'author.full_name'];
 * => 'pepper-and-carrot-by-david-revoy'
 */
trait HasSlugColumn
{
    /**
     * @throws Exception
     * @throws LogicException
     */
    public function updateSlug()
    {
        if (! property_exists($this, 'slugSource')) {
            throw new LogicException('You have to define the $slugSource property');
        }

        $sourceColumnNames = collect($this->slugSource);

        $sourceColumnValues = $sourceColumnNames->map(function (string $name) {
            // Values surrounded by ['-dashes-'] are bypassed to a slug as a string literal
            if (Str::startsWith($name, '-') && Str::endsWith($name, '-')) {
                return Str::between($name, '-', '-');
            }

            $value = null;

            if (($arrNested = collect(explode('.', $name)))->count() > 1) {
                $nestedValue = $this;
                while ($nestedName = $arrNested->shift()) {
                    $nestedValue = $nestedValue->$nestedName;

                    // If property value is null and its name is not the latest
                    // in array it means we can not reach the target value
                    if (is_null($nestedValue) && 0 !== $arrNested->count()) {
                        throw new Exception("Property {$nestedName} in {$name} is null, the target value is not reachable");
                    }
                }

                $value = $nestedValue;
            } else {
                $value = $this->getAttributeValue($name);
            }

            if (is_null($value)) {
                throw new Exception("Failed to update the slug, property {$name} is null");
            }

            if (is_scalar($value) && ! is_bool($value)) {
                return $value;
            }

            throw new Exception("Failed to update the slug, value of {$name} is not suitable for a slug");
        });

        $this->slug = Str::slug($sourceColumnValues->join(' '));
    }

    protected static function bootHasSlugColumn()
    {
        static::creating(function (self $instance) {
            if (true === $instance->doNotUpdateSlugWhenCreating) {
                return;
            }

            $instance->updateSlug();
        });
    }
}
