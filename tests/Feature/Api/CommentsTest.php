<?php

namespace Tests\Feature\Api;

use App\Models\Comic;
use App\Models\Comment;
use App\Models\Commentable;
use App\Models\User;
use Database\Factories\CommentFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class CommentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_post_a_comment(): void
    {
        $response = $this->postComment();

        $response
            ->assertStatus(201)
            ->assertJsonPath('data.id', fn ($id) => is_int($id));
    }

    public function test_can_post_a_comment_reply(): void
    {
        $response = $this->postCommentReply(
            $this->createRootComment(),
        );

        $response
            ->assertStatus(201)
            ->assertJsonPath('data.id', fn ($id) => is_int($id));
    }

    public function test_can_post_a_reply_to_a_reply(): void
    {
        $response = $this->postCommentReply(
            $this->postParentReply(),
        );

        $response
            ->assertStatus(201)
            ->assertJsonPath('data.id', fn ($id) => is_int($id));
    }

    public function test_posted_comment_appears_in_the_database(): void
    {
        $response = $this->postComment(
            user:        ($user = $this->createUser()),
            commentText: ($commentText = $this->randomCommentText()),
        );

        $response->assertJsonPath('data.id', fn ($id) => is_int($id));

        $id = $response->json('data.id');

        $this->assertDatabaseHas(Comment::class, [
            'comment_text' => $commentText,
            'user_id' => $user->id,
            'root_comment_id' => null,
            'parent_comment_id' => null,
            'id' => $id,
        ]);
    }

    public function test_posted_reply_appears_in_the_database(): void
    {
        $response = $this->postCommentReply(
            $comment     = $this->createRootComment(),
            $user        = $this->createUser(),
            $commentText = $this->randomCommentText(),
        );

        $response->assertJsonPath('data.id', fn ($id) => is_int($id));

        $id = $response->json('data.id');

        $this->assertDatabaseHas(Comment::class, [
            'comment_text' => $commentText,
            'user_id' => $user->id,
            'root_comment_id' => $comment->id,
            'parent_comment_id' => $comment->id,
            'id' => $id,
        ]);
    }

    public function test_posted_reply_to_a_reply_appears_in_the_database(): void
    {
        $parentReply = $this->postParentReply(
            $rootComment = $this->createRootComment()
        );

        $replyToReplyId = $this->postCommentReply(
            $parentReply,
            $user        = $this->createUser(),
            $commentText = $this->randomCommentText(),
        )->json('data.id');

        $this->assertDatabaseHas(Comment::class, [
            'comment_text' => $commentText,
            'user_id' => $user->id,
            'root_comment_id' => $rootComment->id,
            'parent_comment_id' => $parentReply->id,
            'id' => $replyToReplyId,
        ]);
    }

    public function test_can_see_posted_comment_in_commentable_resource(): void
    {
        $response = $this->postComment(
            $commentable = $this->createCommentable(),
        );

        $id = $response->json('data.id');

        $response = $this->get(route('root_comments_of_commentable', ['commentable' => $commentable->id]));

        $response
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 1)
                    ->has(
                        'data.0',
                        fn (AssertableJson $json) => $json->where('id', $id)->etc()
                    )
                    ->etc()
            );
    }

    public function test_can_see_posted_reply_in_comment_replies_with_root_resource(): void
    {
        $response = $this->postCommentReply(
            $comment = $this->createRootComment(),
        );

        $id = $response->json('data.id');

        $response = $this->get(route('comment_replies_with_root', ['root' => $comment->id]));

        $response
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 1)
                    ->has(
                        'data.0',
                        fn (AssertableJson $json) => $json->where('id', $id)->etc()
                    )
                    ->etc()
            );
    }

    public function test_can_see_posted_reply_to_a_reply_in_comment_replies_with_root_resource(): void
    {
        $parentReply = $this->postParentReply(
            $rootComment = $this->createRootComment()
        );

        $replyToReplyId = $this->postCommentReply(
            $parentReply,
        )->json('data.id');

        $response = $this->get(route('comment_replies_with_root', ['root' => $rootComment->id]));

        $response
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 2)
                    ->has(
                        'data.0',
                        fn (AssertableJson $json) => $json->where('id', $replyToReplyId)->etc()
                    )
                    ->has(
                        'data.1',
                        fn (AssertableJson $json) => $json->where('id', $parentReply->id)->etc()
                    )
                    ->etc()
            );
    }

    public function test_can_see_posted_comment_in_user_profile_resource(): void
    {
        $commentable = $this->createComic()->commentable;

        $response = $this->postComment(
            $commentable,
            $user = $this->createUser(),
        );

        $id = $response->json('data.id');

        $response = $this->get(route('users.comments.show', ['user' => $user->id]));

        $response
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 1)
                    ->has(
                        'data.0',
                        fn (AssertableJson $json) => $json->where('id', $id)->etc()
                    )
                    ->etc()
            );
    }

    public function test_can_see_posted_reply_in_user_profile_resource(): void
    {
        /** @var Comment */
        $comment = Comment::factory()
                        ->for($this->createUser())
                        ->for($this->createComic()->commentable)
                        ->create();

        $response = $this->postCommentReply(
            $comment,
            $user = $this->createUser(),
        );

        $id = $response->json('data.id');

        $response = $this->get(route('users.comments.show', ['user' => $user->id]));

        $response
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 1)
                    ->has(
                        'data.0',
                        fn (AssertableJson $json) => $json->where('id', $id)->etc()
                    )
                    ->etc()
            );
    }

    public function test_posted_comment_increments_user_comments_cached_count(): void
    {
        $user = $this->createUser();

        $this->assertEquals(0, $user->comments_cached_count);

        $this->postComment(
            user: $user,
        );

        $this->assertEquals(1, $user->refresh()->comments_cached_count);
    }

    public function test_posted_comment_increments_commentable_comments_cached_count(): void
    {
        $commentable = $this->createCommentable();

        $this->assertEquals(0, $commentable->comments_cached_count);

        $this->postComment(
            $commentable,
        );

        $this->assertEquals(1, $commentable->refresh()->comments_cached_count);
    }

    public function test_posted_reply_increments_commentable_comments_cached_count(): void
    {
        $comment = $this->createRootComment();

        $this->assertEquals(1, $comment->commentable->refresh()->comments_cached_count);

        $this->postCommentReply(
            $comment,
        );

        $this->assertEquals(2, $comment->commentable->refresh()->comments_cached_count);
    }

    public function test_posted_reply_to_a_reply_increments_commentable_comments_cached_count(): void
    {
        $parentReply = $this->postParentReply(
            $rootComment = $this->createRootComment()
        );

        $this->assertEquals(2, $rootComment->commentable->refresh()->comments_cached_count);

        $this->postCommentReply(
            $parentReply,
        );

        $this->assertEquals(3, $rootComment->commentable->refresh()->comments_cached_count);
    }

    public function test_posted_reply_increments_root_child_comments_cached_count(): void
    {
        $comment = $this->createRootComment();

        $this->assertEquals(0, $comment->refresh()->root_child_comments_cached_count);

        $this->postCommentReply(
            $comment,
        );

        $this->assertEquals(1, $comment->refresh()->root_child_comments_cached_count);
    }

    public function test_posted_reply_to_a_reply_increments_root_child_comments_cached_count(): void
    {
        $parentReply = $this->postParentReply(
            $rootComment = $this->createRootComment()
        );

        $this->assertEquals(1, $rootComment->refresh()->root_child_comments_cached_count);

        $this->postCommentReply(
            $parentReply,
        );

        $this->assertEquals(2, $rootComment->refresh()->root_child_comments_cached_count);
    }

    public function test_counts_comments_of_user_correctly(): void
    {
        $user = $this->createUser();

        $this->assertEquals(0, $user->countComments());

        // The comment of our user - should be counted
        $this->postComment(user: $user);

        $this->assertEquals(1, $user->countComments());

        // A comment of another user - should not affect the count
        $this->postComment();

        $this->assertEquals(1, $user->countComments());
    }

    protected function postComment(?Commentable $commentable = null, ?User $user = null, ?string $commentText = null): TestResponse
    {
        $response = $this->actingAs($user ?? $this->createUser())->post(
            route('commentables.comments.store', [
                'commentable' => ($commentable ?? $this->createCommentable())->id,
            ]),
            [
                'comment_text' => $commentText ?? $this->randomCommentText(),
            ],
        );

        return $response;
    }

    protected function createRootComment(): Comment
    {
        return Comment::factory()
            ->for($this->createUser())
            ->for($this->createCommentable())
            ->create();
    }

    protected function postParentReply(?Comment $rootComment = null): Comment
    {
        return Comment::find($this->postCommentReply(
            comment: ($rootComment ?? $this->createRootComment()),
        )->json('data.id'));
    }

    protected function postCommentReply(Comment $comment, ?User $user = null, ?string $commentText = null): TestResponse
    {
        $response = $this->actingAs($user ?? $this->createUser())->post(
            route('comments.replies.store', [
                'comment' => $comment->id,
            ]),
            [
                'comment_text' => $commentText ?? $this->randomCommentText(),
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
        return $this->createComic()->commentable;
    }

    protected function createUser(): User
    {
        return User::factory()->create();
    }
}
