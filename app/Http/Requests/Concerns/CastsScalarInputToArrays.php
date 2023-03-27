<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Support\Arr;

trait CastsScalarInputToArrays
{
    protected function getKeysWithArrayRule(array $rules): array
    {
        return array_keys(Arr::where(
            $rules,
            fn ($rule) => in_array('array', explode('|', $rule)),
        ));
    }

    protected function castScalarsToArrays(array $keys): void
    {
        foreach ($keys as $key) {
            if (is_scalar($val = $this->input($key))) {
                $this->merge([
                    $key => (array) $val,
                ]);
            }
        }
    }
}