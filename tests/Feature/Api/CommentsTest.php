<?php

namespace Tests\Feature\Api;

use App\Models\Author;
use App\Models\Comic;
use App\Models\Comment;
use App\Models\Commentable;
use App\Models\User;
use Database\Factories\CommentFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class CommentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_post_a_comment(): void
    {
        $response = $this->postComment(
            $this->createCommentable(),
            $this->createUser(),
            $this->randomCommentText(),
        );

        $response->assertStatus(201);
    }

    public function test_posted_comment_appears_in_the_database(): void
    {
        $response = $this->postComment(
            $this->createCommentable(),
            $user = $this->createUser(),
            $commentText = $this->randomCommentText(),
        );

        $response->assertJsonPath('data.id', fn ($id) => is_int($id));

        $id = $response->json('data.id');

        $this->assertDatabaseHas(Comment::class, [
            'comment_text' => $commentText,
            'user_id' => $user->id,
            'id' => $id
        ]);
    }

    public function test_can_see_posted_comment_in_commentable_resource(): void
    {
        $response = $this->postComment(
            $commentable = $this->createCommentable(),
            $this->createUser(),
            $this->randomCommentText(),
        );

        $id = $response->json('data.id');

        $response = $this->get(route('root_comments_of_commentable', ['commentable' => $commentable->id]));
    
        $response
            ->assertJson(fn (AssertableJson $json) =>
                $json
                    ->has('data', 1)
                    ->has('data.0', fn (AssertableJson $json) =>
                        $json->where('id', $id)->etc()
                    )
                    ->etc()
            );
    }

    public function test_can_see_posted_comment_in_user_profile(): void
    {
        $commentable = $this->createComic()->commentable;

        $response = $this->postComment(
            $commentable,
            $user = $this->createUser(),
            $this->randomCommentText(),
        );
        
        $id = $response->json('data.id');

        $response = $this->get(route('users.comments.show', ['user' => $user->id]));

        $response
            ->assertJson(fn (AssertableJson $json) =>
                $json
                    ->has('data', 1)
                    ->has('data.0', fn (AssertableJson $json) =>
                        $json->where('id', $id)->etc()
                    )
                    ->etc()
            );
    }

    public function test_increments_commentable_comments_cached_count(): void
    {
        $commentable = $this->createCommentable();

        $this->assertEquals(0, $commentable->comments_cached_count);

        $this->postComment(
            $commentable,
            $this->createUser(),
            $this->randomCommentText(),
        );
        
        $this->assertEquals(1, $commentable->refresh()->comments_cached_count);
    }

    protected function postComment(Commentable $commentable, User $user, string $commentText): TestResponse
    {
        $response = $this->actingAs($user)->post(
            route('commentables.comments.store', [
                'commentable' => $commentable->id
            ]),
            [
                'comment_text' => $commentText,
            ],
        );

        return $response;
    }

    protected function randomCommentText(): string
    {
        return Arr::random(CommentFactory::TEXTS);
    }

    protected function createComic(): Comic
    {
        return Comic::factory()->create();
    }

    protected function createCommentable(): Commentable
    {
        return Commentable::factory()->create();
    }

    protected function createUser(): User
    {
        return User::factory()->create();
    }
}
