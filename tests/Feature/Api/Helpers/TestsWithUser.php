<?php

namespace Tests\Feature\Api\Helpers;

use App\Models\User;

trait TestsWithUser
{
    protected function createUser(): User
    {
        return User::factory()->create();
    }
}
