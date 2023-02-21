<?php

namespace Tests\Feature\Resources;

use App\Models\Comic;
use App\Models\Episode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ComicMainInfoResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_resource_response_json_structure_matches_specification(): void
    {
        $this->actingAs($user = $this->createUser());

        $comic = Comic::factory()
                    ->has(Episode::factory()->count(3))
                    ->hasCachedLatestViewedEpisodeByUser($user)
                    ->hasMainCharacters()
                    ->hasOtherComicsByAuthor()
                    ->hasTagsAttached(3)
                    ->hasComicUserListEntryAttached($user->comicUserLists->first())
                    ->create();

        $response = $this->get(route(
            'comics.main_info',
            ['comic' => modelKey($comic)],
        ));

        $specification = [
            'data' => [
                'id' => 'integer',
                'slug' => 'string',
                'author' => [
                    'id' => 'integer',
                    'fullName' => 'string',
                ],
                'title' => 'string',
                'description' => 'string',
                'statistics' => [
                    'likes' => [
                        'total' => 'integer',
                    ],
                    'views' => [
                        'total' => 'integer',
                    ],
                    'comments' => [
                        'total' => 'integer',
                    ],
                ],
                'comicPoster' => [
                    'medium' => 'string',
                ],
                'comicHeaderBackground' => [
                    'medium' => 'string',
                ],
                'commentable' => [
                    'id' => 'integer',
                ],
                'comicUserListSlug' => 'string',
                'cachedLatestViewedEpisode' => [
                    'id' => 'integer',
                    'number' => 'integer',
                    'title' => 'string',
                    'publishedAt' => 'string',
                ],
                'episodes' => [
                    '0' => [
                        'id' => 'integer',
                        'title' => 'string',
                        'number' => 'integer',
                        'publishedAt' => 'string',
                        'poster' => [
                            'medium' => 'string',
                        ],
                        'viewable' => [
                            'viewsCachedCount' => 'integer',
                            'isSeenByUser' => 'boolean',
                        ],
                    ],
                ],
                'mainCharacters' => [
                    '0' => [
                        'id' => 'integer',
                        'description' => 'string',
                        'roleType' => 'string',
                        'character' => [
                            'id' => 'integer',
                            'fullName' => 'string',
                            'poster' => [
                                'medium' => 'string',
                            ],
                        ],
                    ],
                ],
                'otherComicsByAuthor' => [
                    '0' => [
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
            ->assertStatus(200)
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
