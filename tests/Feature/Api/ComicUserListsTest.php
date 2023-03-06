<?php

namespace Tests\Feature\Api;

use App\Events\EpisodeViewedByUser;
use App\Models\Comic;
use App\Models\ComicUserList;
use App\Models\ComicUserListEntry;
use App\Models\Episode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ComicUserListsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_put_comic_in_comic_user_list(): void
    {
        $user  = $this->createUser();
        $comic = $this->createComic();
        /** @var ComicUserList */
        $comicUserList = $user->comicUserLists()->first();
        $this->assertCount(0, $comicUserList->comicUserListEntries);

        $this->actingAs($user);
        $response = $this->putComic($comicUserList, $comic);

        $response->assertOk();
        $comicUserList->refresh();
        $this->assertCount(1, $comicUserList->comicUserListEntries);
        $this->assertEquals($comic->id, $comicUserList->comicUserListEntries->first()->comic->id);
    }

    public function test_can_see_comic_in_comic_user_list_entries(): void
    {
        $user  = $this->createUser();
        $comic = $this->createComic();
        /** @var ComicUserList */
        $comicUserList = $user->comicUserLists()->first();

        $this->actingAs($user);
        $this->putComic($comicUserList, $comic);

        $response = $this->get(route(
            'comic_user_lists.entries',
            ['comicUserList' => modelKey($comicUserList)],
        ));

        $response
            ->assertOk()
            ->assertJsonPath('data.0.comic.id', fn ($id) => $comic->id === $id);
    }

    public function test_json_structure_of_the_comic_user_list_entries_resource_response_matches_the_specification(): void
    {
        /** @var ComicUserListEntry $comicUserListEntry */
        $comicUserListEntry = ComicUserListEntry::factory()
                                        ->for($user = $this->createUser())
                                        ->for($user->comicUserLists()->first())
                                        ->for(
                                            Comic::factory()
                                                ->has(Episode::factory())
                                        )
                                        ->create();

        event(new EpisodeViewedByUser(
            $comicUserListEntry->comic->episodes->first(),
            $user,
        ));

        $response = $this->get(route(
            'comic_user_lists.entries',
            ['comicUserList' => modelKey($comicUserListEntry->comicUserList)],
        ));

        $specification = [
            'data' => [
                '0' => [
                    'id' => 'integer',
                    'comic' => [
                        'id' => 'integer',
                        'title' => 'string',
                        'slug' => 'string',
                        'episodesLeft' => 'integer',
                        'cachedLatestViewedEpisode' => [
                            'id' => 'integer',
                            'title' => 'string',
                            'number' => 'integer',
                            'publishedAt' => 'string',
                        ],
                        'comicPoster' => [
                            'medium' => 'string',
                        ],
                        'author' => [
                            'id' => 'integer',
                            'fullName' => 'string',
                        ],
                    ],
                ],
            ],
            'links' => 'array',
            'meta' => 'array',
        ];

        $response
            ->assertOk()
            ->assertJson(
                fn (AssertableJson $json) => $json
                ->whereAllType(Arr::dot($specification))
            );
    }

    public function test_can_remove_comic_from_comic_user_list(): void
    {
        $user  = $this->createUser();
        $comic = $this->createComic();
        /** @var ComicUserList */
        $comicUserList = $user->comicUserLists()->first();

        $this->actingAs($user);
        $this->putComic($comicUserList, $comic);
        $this->assertCount(1, $comicUserList->refresh()->comicUserListEntries);

        $response = $this->post(route(
            'comic_user_lists.remove_comic',
            ['comic' => $comic],
        ));

        $response->assertOk();
        $this->assertCount(0, $comicUserList->refresh()->comicUserListEntries);
    }

    protected function putComic(ComicUserList $comicUserList, Comic $comic): TestResponse
    {
        return $this->post(route(
            'comic_user_lists.by_slug.put_comic',
            [
                'comicUserListSlug' => $comicUserList->slug,
                'comic' => $comic,
            ],
        ));
    }

    protected function createComic(): Comic
    {
        return Comic::factory()->create();
    }

    protected function createUser(): User
    {
        return User::factory()->create();
    }
}
