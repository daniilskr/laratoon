<?php

namespace Tests\Feature\Api;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Likeable;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class LikeableTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_post_like(): void
    {
        $this->actingAs($this->createUser());

        $response = $this->postLike();

        $response->assertStatus(201);
    }

    public function test_can_delete_like(): void
    {
        $this->actingAs($this->createUser());
        $this->postLike($likeable = $this->createLikeable());

        $response = $this->deleteLike($likeable);
        $response->assertStatus(200);
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

        $this->assertEquals(0, ($likeable = $this->createLikeable())->likes_cached_count);

        $this->postLike($likeable);

        $this->assertEquals(1, $likeable->refresh()->likes_cached_count);
    }

    public function test_deleted_like_decrements_likeable_likes_cached_count(): void
    {
        $this->actingAs($this->createUser());
        $this->postLike($likeable = $this->createLikeable());

        $this->assertEquals(1, $likeable->refresh()->likes_cached_count);

        $this->deleteLike($likeable);

        $this->assertEquals(0, $likeable->refresh()->likes_cached_count);
    }

    public function test_can_get_request_user_like_from_likeable_model(): void
    {
        $this->actingAs($user = $this->createUser());
        $this->postLike($likeable = $this->createLikeable());

        $this->assertNotNull($likeable->refresh()->requestUserLike);
        $this->assertEquals($user->id, $likeable->refresh()->requestUserLike->user->id);
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

    protected function createLikeable(): Likeable
    {
        return Comment::factory()
                ->state([
                    'commentable_id' => 0,
                ])
                ->for(User::factory())
                ->create()->likeable;
    }

    protected function createUser(): User
    {
        return User::factory()->create();
    }
}
