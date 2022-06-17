<?php

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

if (! function_exists('modelKey')) {
    /**
     * Если модель, то достает из неё значение primary key (оно должно быть интом), иначе мапит элементы коллекции в инты.
     */
    function modelKey(Model|int $model): int
    {
        return $model instanceof Model ? $model->getKey() : $model;
    }
}

if (! function_exists('modelKeys')) {
    /**
     * Если это коллекция моделей, то достает из них значения primary key (оно должно быть интом), иначе мапит элементы коллекции в инты.
     * @param Model[]|int[]|Collection $keys
     * @return int[]|Collection
     */
    function modelKeys($keys)
    {
        $keys = collected($keys);

        if ($keys instanceof EloquentCollection) {
            $keys = $keys->modelKeys();
        }

        return $keys->map(fn (int $i) => $i);
    }
}

if (! function_exists('whereHasIn')) {
    /**
     * Shorthand for whereHas('relationship', fn($q) => $q->whereIn('column', $values)).
     */
    function whereHasIn(EloquentBuilder $query, string $relationship, string $column, Collection $values)
    {
        return $query->whereHas(
            $relationship,
            fn ($q) => $q->whereIn($column, $values->toArray()),
        );
    }
}

if (! function_exists('whereHasNoneIn')) {
    /**
     * Shorthand for whereHas('relationship', fn($q) => $q->whereIn('column', $values), '=', 0).
     */
    function whereHasNoneIn(EloquentBuilder $query, string $relationship, string $column, Collection $values)
    {
        return $query->whereHas(
            $relationship,
            fn ($q) => $q->whereIn($column, $values->toArray()),
            '=',
            0
        );
    }
}

if (! function_exists('whereHasAllUnique')) {
    /**
     * Where has all models of relationship with unique values
     * like  whereHasAllUnique($query, 'tags', 'slug', ['magic', 'fantasy']).
     */
    function whereHasAllUnique(EloquentBuilder $query, string $relationship, string $column, Collection $values)
    {
        $values = $values->unique();

        return $query->whereHas(
            $relationship,
            fn ($q) => $q->whereIn($column, $values->toArray()),
            '=',
            $values->count()
        );
    }
}

if (! function_exists('whereKeyInRaw')) {
    /**
     * обертка whereIntegerInRaw(qualified-key-name, $keys).
     * @param Model[]|int[]|Collection $keys
     */
    function whereKeyInRaw(EloquentBuilder $query, $keys)
    {
        return $query->whereIntegerInRaw(
            $query->getModel()->getQualifiedKeyName(),
            modelKeys($keys)
        );
    }
}

if (! function_exists('collected')) {
    /**
     * Если $collectable не наследует Illuminate\Support\Collection, то оборачивает его в коллекцию, иначе возвращает как есть
     * Нужно, потому что collect() меняет тип коллекции на Illuminate\Support\Collection, даже если она изначально была Eloquent коллекцией.
     */
    function collected($collectable)
    {
        return $collectable instanceof Collection
                    ? $collectable
                    : collect($collectable);
    }
}

if (! function_exists('repeat')) {
    /**
     * Обертка над for($i=0,$i<$times,$i++){}.
     */
    function repeat(int $times, callable $callback)
    {
        if ($times < 0) {
            throw new LogicException('There is no way to repeat something a negative number of times');
        }

        for ($i = 0; $i < $times; $i++) {
            $callback($i);
        }
    }
}

if (! function_exists('isDebug')) {
    /**
     * Проверяет в конфиге, находится ли приложение в дебаг режиме.
     */
    function isDebug()
    {
        return true === config('app.debug', false);
    }
}

if (! function_exists('isLocal')) {
    /**
     * Проверяет в конфиге, запущено ли приложение в локальном окружении(не прод).
     */
    function isLocal()
    {
        return 'local' === config('app.env', 'production');
    }
}

if (! function_exists('genRange')) {
    /**
     * range() implemented as generator, copy-pasted from the documentation(xrange)
     * $start is treated as $limit when $limit is null
     * https://www.php.net/manual/en/language.generators.overview.php.
     */
    function genRange($start, $limit = null, $step = 1)
    {
        if (is_null($limit)) {
            $limit = $start;
            $start = 0;
        }

        if ($start <= $limit) {
            if ($step <= 0) {
                throw new LogicException('Step must be positive');
            }

            for ($i = $start; $i <= $limit; $i += $step) {
                yield $i;
            }
        } else {
            if ($step >= 0) {
                throw new LogicException('Step must be negative');
            }

            for ($i = $start; $i >= $limit; $i += $step) {
                yield $i;
            }
        }
    }
}
