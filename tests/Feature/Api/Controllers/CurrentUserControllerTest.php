<?php

namespace Tests\Feature\Api\Controllers;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CurrentUserControllerTest extends TestCase
{
    public function test_json_structure_of_the_resource_response_matches_the_specification(): void
    {
        $this->actingAs($this->createUser());

        $response = $this->get(route('current_user'));

        $specification = [
            'data' => [
                'id' => 'integer',
                'email' => 'string',
                'fullName' => 'string',
                'comicUserLists' => [
                    '0' => [
                        'id' => 'integer',
                        'name' => 'string',
                        'slug' => 'string',
                        'color' => 'string',
                    ],
                ],
                'avatar' => [
                    'medium' => 'string',
                ],
            ],
        ];

        $response
            ->assertOk()
            ->assertJson(
                fn (AssertableJson $json) => $json
                ->whereAllType(Arr::dot($specification))
            );
    }

    protected function createUser(): User
    {
        return User::factory()->create();
    }
}
