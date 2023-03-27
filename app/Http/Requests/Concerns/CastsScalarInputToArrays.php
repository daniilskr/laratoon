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

    /** 
     * @param list<string> $keys If not provided will use (not nested) keys from $this->rules() that have 'array' rule  
     */
    protected function castScalarsToArrays(array $keys = null): void
    {
        if (is_null($keys)) {
            $keys = $this->getKeysWithArrayRule($this->rules());
        }

        foreach ($keys as $key) {
            if (is_scalar($val = $this->input($key))) {
                $this->merge([
                    $key => (array) $val,
                ]);
            }
        }
    }
}