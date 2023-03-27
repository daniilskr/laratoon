<?php

namespace Tests\Feature\Api;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Likeable;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class LikesTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_post_like(): void
    {
        $this->actingAs($this->createUser());

        $response = $this->postLike($this->createComment()->likeable);

        $response->assertStatus(201);
    }

    public function test_can_delete_like(): void
    {
        $this->actingAs($this->createUser());
        $this->postLike($likeable = $this->createComment()->likeable);

        $response = $this->deleteLike($likeable);
        $response->assertOk();
    }

    public function test_posted_like_appears_in_the_database(): void
    {
        $this->actingAs($user = $this->createUser());
        $this->postLike($likeable = $this->createLikeable());

        $this->assertDatabaseHas(Like::class, [
            'user_id' => $user->id,
            'likeable_id' => $likeable->id,
        ]);
    }

    public function test_deleted_like_is_not_present_in_the_database(): void
    {
        $this->actingAs($user = $this->createUser());
        $this->postLike($likeable = $this->createLikeable());
        $this->deleteLike($likeable);

        $this->assertDatabaseMissing(Like::class, [
            'user_id' => $user->id,
            'likeable_id' => $likeable->id,
        ]);
    }

    public function test_posted_like_increments_likeable_likes_cached_count(): void
    {
        $this->actingAs($this->createUser());

        $this->assertEquals(0, ($likeable = $this->createComment()->likeable)->likes_cached_count);

        $this->postLike($likeable);

        $this->assertEquals(1, $likeable->refresh()->likes_cached_count);
    }

    public function test_deleted_like_decrements_likeable_likes_cached_count(): void
    {
        $this->actingAs($this->createUser());
        $this->postLike($likeable = $this->createComment()->likeable);

        $this->assertEquals(1, $likeable->refresh()->likes_cached_count);

        $this->deleteLike($likeable);

        $this->assertEquals(0, $likeable->refresh()->likes_cached_count);
    }

    public function test_posted_like_increments_user_likes_cached_count(): void
    {
        $this->actingAs($user = $this->createUser());

        $this->assertEquals(0, $user->likes_cached_count);

        $this->postLike();

        $this->assertEquals(1, $user->refresh()->likes_cached_count);
    }

    public function test_deleted_like_decrements_user_likes_cached_count(): void
    {
        $this->actingAs($user = $this->createUser());
        $this->postLike($likeable = $this->createLikeable());

        $this->assertEquals(1, $user->refresh()->likes_cached_count);

        $this->deleteLike($likeable);

        $this->assertEquals(0, $user->refresh()->likes_cached_count);
    }

    public function test_counts_likes_of_user_correctly(): void
    {
        $this->actingAs($user = $this->createUser());
        $this->assertEquals(0, $user->countLikes());

        // Should count
        $this->postLike();
        $this->assertEquals(1, $user->countLikes());

        // Should not count
        $this->actingAs($this->createUser());
        $this->postLike();
        $this->assertEquals(1, $user->countLikes());
    }

    public function test_counts_stars_of_user_correctly(): void
    {
        $ourUser    = $this->createUser();
        $ourComment = $this->createComment($ourUser);
        $otherUser  = $this->createUser();

        $this->assertEquals(0, $ourUser->countStars());

        // Should not count like on own comment as a star
        $this->actingAs($ourUser);
        $this->postLike($ourComment->likeable);
        $this->assertEquals(0, $ourUser->countStars());

        // Should count other's user like on our comment as a star
        $this->actingAs($otherUser);
        $this->postLike($ourComment->likeable);
        $this->assertEquals(1, $ourUser->countStars());
    }

    public function test_posted_like_increments_user_stars_cached_count(): void
    {
        $userGiftsStars = $this->createUser();
        $userGetsStars  = $this->createUser();

        $this->assertEquals(0, $userGetsStars->stars_cached_count);

        $this->actingAs($userGiftsStars);
        $this->postLike($this->createComment($userGetsStars)->likeable);

        $this->assertEquals(1, $userGetsStars->refresh()->stars_cached_count);
    }

    public function test_deleted_like_decrements_user_stars_cached_count(): void
    {
        $userGiftsStars = $this->createUser();
        $userGetsStars  = $this->createUser();

        $this->actingAs($userGiftsStars);
        $this->postLike($likeable = $this->createComment($userGetsStars)->likeable);
        $this->assertEquals(1, $userGetsStars->refresh()->stars_cached_count);

        $this->deleteLike($likeable);
        $this->assertEquals(0, $userGetsStars->refresh()->stars_cached_count);
    }

    public function test_posted_like_on_own_comment_does_not_increment_user_stars_cached_count(): void
    {
        $this->actingAs($user = $this->createUser());
        $this->assertEquals(0, $user->stars_cached_count);

        $this->postLike($this->createComment($user)->likeable);
        $this->assertEquals(0, $user->refresh()->stars_cached_count);
    }

    public function test_deleted_like_on_own_comment_does_not_decrement_user_stars_cached_count(): void
    {
        $this->actingAs($user = $this->createUser());

        $this->postLike($likeable = $this->createComment($user)->likeable);
        $this->assertEquals(0, $user->refresh()->stars_cached_count);

        $this->deleteLike($likeable);
        $this->assertEquals(0, $user->refresh()->stars_cached_count);
    }

    public function test_can_get_request_user_like_from_likeable_model(): void
    {
        $this->actingAs($user = $this->createUser());
        $this->postLike($likeable = $this->createLikeable());

        $this->assertNotNull($likeable->refresh()->getRequestUserLike());
        $this->assertEquals($user->id, $likeable->refresh()->getRequestUserLike()->user->id);
    }

    protected function postLike(?Likeable $likeable = null): TestResponse
    {
        return $this->post(
            route('likeables.like.store', [
                'likeable' => $likeable ?? $this->createLikeable(),
            ])
        );
    }

    protected function deleteLike(?Likeable $likeable = null): TestResponse
    {
        return $this->delete(route('likeables.like.destroy', [
            'likeable' => $likeable ?? $this->createLikeable(),
        ]));
    }

    protected function createComment(?User $user = null): Comment
    {
        return Comment::factory()
                ->state([
                    'commentable_id' => 0,
                ])
                ->for($user ?? $this->createUser())
                ->create();
    }

    protected function createLikeable(): Likeable
    {
        return $this->createComment()->likeable;
    }

    protected function createUser(): User
    {
        return User::factory()->create();
    }
}
