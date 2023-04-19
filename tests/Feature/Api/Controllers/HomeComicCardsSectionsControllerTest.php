<?php

namespace Tests\Feature\Api\Controllers;

use App\Models\Comic;
use Database\Factories\GenreFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class HomeComicCardsSectionsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_comic_cards_sections_match_the_specification(): void
    {
        Comic::factory(5)->hasGenresAttached(count(GenreFactory::GENRES))->create();

        $response = $this->get('/api/home-comic-cards-sections');

        $specification = [
            0 => [
                'type' => 'string',
                'title' => 'string',
                'comicCards' => [
                    0 => [
                        'id' => 'integer',
                        'slug' => 'string',
                        'title' => 'string',
                        'description' => 'string',
                        'author' => [
                            'id' => 'integer',
                            'fullName' => 'string',
                        ],
                        'statistics' => [
                            'likes' => [
                                'total' => 'integer',
                            ],
                        ],
                        'comicPoster' => [
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
