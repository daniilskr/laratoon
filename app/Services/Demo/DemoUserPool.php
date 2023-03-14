<?php

namespace App\Services\Demo;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class DemoUserPool
{
    public function __construct(
        public readonly int $minDemoUserId,
        public readonly int $maxDemoUserId,
    ) {
    }

    public function getDemoUserIdsRange(): array
    {
        return [$this->minDemoUserId, $this->maxDemoUserId];
    }

    public function take(): ?User
    {
        $user = User::where('issued_for_demo', false)
                ->whereBetween('id', $this->getDemoUserIdsRange())
                ->first();

        if (is_null($user)) {
            Log::emergency('out of available demo accounts');

            return null;
        }

        $user->issued_for_demo = true;
        $user->save();

        return $user;
    }
}
