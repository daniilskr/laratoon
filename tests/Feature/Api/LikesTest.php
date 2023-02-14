<?php

namespace Tests\Feature\Api;

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
        $response = $this->postLike();

        $response->assertStatus(201);
    }

    public function test_can_delete_like(): void
    {
        $this->postLike(
            $user     = $this->createUser(),
            $likeable = $this->createLikeable(),
        );

        $response = $this->deleteLike($user, $likeable);

        $response->assertStatus(200);
    }

    public function test_posted_like_appears_in_the_database(): void
    {
        $this->postLike(
            $user     = $this->createUser(),
            $likeable = $this->createLikeable(),
        );

        $this->assertDatabaseHas(Like::class, [
            'user_id' => $user->id,
            'likeable_id' => $likeable->id,
        ]);
    }

    public function test_deleted_like_is_not_present_in_the_database(): void
    {
        $this->postLike(
            $user     = $this->createUser(),
            $likeable = $this->createLikeable(),
        );

        $this->deleteLike($user, $likeable);

        $this->assertDatabaseMissing(Like::class, [
            'user_id' => $user->id,
            'likeable_id' => $likeable->id,
        ]);
    }

    protected function postLike(?User $user = null, ?Likeable $likeable = null): TestResponse
    {
        return $this->actingAs($user ?? $this->createUser())->post(
            route('likeables.like.store', [
                'likeable' => $likeable ?? $this->createLikeable(),
            ])
        );
    }

    protected function deleteLike(?User $user = null, ?Likeable $likeable = null): TestResponse
    {
        return $this->actingAs($user ?? $this->createUser())->delete(route('likeables.like.destroy', [
            'likeable' => $likeable ?? $this->createLikeable(),
        ]));
    }

    protected function createLikeable(): Likeable
    {
        return Likeable::factory()->create();
    }

    protected function createUser(): User
    {
        return User::factory()->create();
    }
}
