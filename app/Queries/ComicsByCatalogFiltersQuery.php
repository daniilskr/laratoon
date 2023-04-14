<?php

namespace App\Queries;

use App\Models\Comic;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * @phpstan-type Filters array{
 *  'tags'?: list<string>,
 *  'genres'?: list<string>,
 *  'statuses'?: list<string>,
 *  'year_from'?: string,
 *  'year_to'?: string,
 * }
 */
class ComicsByCatalogFiltersQuery
{
    /**
     * @phpstan-param Filters $filters
     */
    public static function newQuery(array $filters): Builder
    {
        $query = Comic::query();

        if (array_key_exists('tags', $filters)) {
            whereHasAllUnique($query, 'comicTags', 'slug', collect($filters['tags']));
        }

        if (array_key_exists('genres', $filters)) {
            whereHasAllUnique($query, 'genres', 'slug', collect($filters['genres']));
        }

        if (array_key_exists('statuses', $filters)) {
            whereHasIn($query, 'publicationStatus', 'slug', collect($filters['statuses']));
        }

        if (array_key_exists('year_from', $filters)) {
            $query->whereYear('publishing_start', '>=', (string) $filters['year_from']);
        }

        if (array_key_exists('year_to', $filters)) {
            $query->whereYear('publishing_end', '<=', (string) $filters['year_to']);
        }

        return $query;
    }

    /**
     * @phpstan-param Filters $filters
     *
     * @throws ValidationException
     */
    public static function newValidatedQuery(array $filters): Builder
    {
        return self::newQuery(
            Validator::make($filters, self::getRules())->validate()
        );
    }

    /**
     * Get validator rules for $filter of self::queryWithFilters($filter)
     */
    public static function getRules(): array
    {
        return [
            'tags' => 'array',
            'tags.*' => 'alpha_dash:ascii',

            'genres' => 'array',
            'genres.*' => 'alpha_dash:ascii',

            'statuses' => 'array',
            'statuses.*' => 'alpha_dash:ascii',

            'year_from' => 'integer',
            'year_to' => 'integer',
        ];
    }
}
