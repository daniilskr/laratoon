<?php

namespace Tests\Feature\Api\Controllers;

use App\Models\Comic;
use App\Models\Episode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class EpisodeMainInfoControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_json_structure_of_the_resource_response_matches_the_specification(): void
    {
        /** @var Comic */
        $comic = Comic::factory()
            ->has(Episode::factory(1))
            ->create();

        /** @var Episode */
        $episode = $comic->episodes->first();

        $response = $this->get(route(
            'comic_by_slug.episode_by_number.main_info',
            [
                'comicSlug' => $comic->slug,
                'episodeNumber' => $episode->number,
            ],
        ));

        $specification = [
            'data' => [
                'id' => 'integer',
                'title' => 'string',
                'number' => 'integer',
                'comic' => [
                    'id' => 'integer',
                    'title' => 'string',
                    'slug' => 'string',
                ],
                'commentable' => [
                    'id' => 'integer',
                ],
                'pages' => [
                    '0' => [
                        'id' => 'integer',
                        'order' => 'integer',
                        'image' => [
                            'medium' => 'string',
                        ],
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
