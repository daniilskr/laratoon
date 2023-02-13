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

class CommentRepliesTest extends TestCase
{
    use RefreshDatabase,
        Helpers\TestsWithUser;

    public function test_can_post_a_comment_reply(): void
    {
        $response = $this->postCommentReply(
            $this->createRootComment(),
            $this->createUser(),
            $this->randomCommentText(),
        );

        $response->assertStatus(201);
    }

    public function test_can_post_reply_to_a_reply()
    {
        $response = $this->postCommentReply(
            $this->postParentReply(),
            $this->createUser(),
            $this->randomCommentText(),
        );

        $response
            ->assertStatus(201)
            ->assertJsonPath('data.id', fn ($id) => is_int($id));
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

    public function test_posted_reply_to_a_reply_appears_in_database()
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

    public function test_can_see_posted_reply_in_comment_replies_with_root_resource(): void
    {
        $response = $this->postCommentReply(
            $comment = $this->createRootComment(),
            $this->createUser(),
            $this->randomCommentText(),
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
            $this->createUser(),
            $this->randomCommentText(),
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

    public function test_can_see_posted_reply_in_user_profile(): void
    {
        $comment = Comment::factory()
                        ->for($this->createUser())
                        ->for(Comic::factory()->create()->commentable)
                        ->create();

        $response = $this->postCommentReply(
            $comment,
            $user = $this->createUser(),
            $this->randomCommentText(),
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

    public function test_posted_reply_increments_commentable_comments_cached_count(): void
    {
        $comment = $this->createRootComment();

        $this->assertEquals(1, $comment->commentable->refresh()->comments_cached_count);

        $this->postCommentReply(
            $comment,
            $this->createUser(),
            $this->randomCommentText(),
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
            $this->createUser(),
            $this->randomCommentText(),
        );

        $this->assertEquals(3, $rootComment->commentable->refresh()->comments_cached_count);
    }

    protected function createRootComment(): Comment
    {
        return Comment::factory()
            ->for($this->createUser())
            ->for(Commentable::factory()->create())
            ->create();
    }

    protected function postParentReply(?Comment $rootComment = null): Comment
    {
        return Comment::find($this->postCommentReply(
            $rootComment ?? $this->createRootComment(),
            $this->createUser(),
            $this->randomCommentText(),
        )->json('data.id'));
    }

    protected function postCommentReply(Comment $comment, User $user, string $commentText): TestResponse
    {
        $response = $this->actingAs($user)->post(
            route('comments.replies.store', [
                'comment' => $comment->id,
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
}
