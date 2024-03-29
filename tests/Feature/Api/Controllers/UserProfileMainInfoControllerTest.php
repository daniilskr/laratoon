<?php

namespace Tests\Feature\Api\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserProfileMainInfoControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_json_structure_of_the_resource_response_matches_the_specification(): void
    {
        $user = User::factory()
                    ->create();

        $response = $this->get(route(
            'users.profile_main_info',
            ['user' => $user],
        ));

        $specification = [
            'data' => [
                'id' => 'integer',
                'name' => 'string',
                'statistics' => [
                    'likes' => 'integer',
                    'comments' => 'integer',
                    'views' => 'integer',
                    'stars' => 'integer',
                ],
                'avatar' => [
                    'medium' => 'string',
                ],
                'comicUserLists' => [
                    '0' => [
                        'id' => 'integer',
                        'name' => 'string',
                        'slug' => 'string',
                        'color' => 'string',
                    ],
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
}
