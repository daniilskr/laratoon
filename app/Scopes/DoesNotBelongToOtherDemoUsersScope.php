<?php

namespace App\Scopes;

use App\Services\Demo\DemoUsersPool;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class DoesNotBelongToOtherDemoUsersScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        /** @var DemoUsersPool */
        $pool = resolve(DemoUsersPool::class);

        $builder->whereHas(
            'user',
            fn ($qU) => $qU->whereBetween('id', $pool->getDemoUserIdsRange())
                    ->when(
                        request()?->user()?->id,
                        fn ($q, $userId) => $q->where('id', '<>', $userId)
                    ),
            '=',
            0
        );
    }
}
