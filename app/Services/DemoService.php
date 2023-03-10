<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Likeable;
use App\Models\User;
use App\Scopes\DoesNotBelongToOtherDemoUsersScope;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DemoService
{
    public function __construct(
        public readonly int $minDemoUserId,
        public readonly int $maxDemoUserId,
    ) {
    }

    public function getDemoUserIdsRange(): array
    {
        return [$this->minDemoUserId, $this->maxDemoUserId];
    }

    public function getDemoUserToAuth(): ?User
    {
        $user = User::where('issued_for_demo', false)
                ->whereBetween('id', $this->getDemoUserIdsRange())
                ->first();

        if (is_null($user)) {
            Log::emergency('out of available demo accounts');

            return null;
        }

        $user->issued_for_demo = true;
        $user->save();

        return $user;
    }

    public function cleanUpDemoUserData(): void
    {
        $this->cleanUpComments();
        $this->deleteAllSessions();
        $this->allowToIssueUsedDemoUsers();
    }

    protected function applyBelongsToDemoUsersScope(EloquentBuilder $query): EloquentBuilder
    {
        return $query->withoutGlobalScope(DoesNotBelongToOtherDemoUsersScope::class)
            ->whereHas(
                'user',
                fn ($qU) => $qU->whereBetween('id', $this->getDemoUserIdsRange())
            );
    }

    protected function cleanUpComments(): void
    {
        Likeable::whereHasMorph(
            'owner',
            Comment::class,
            fn ($qC) => $this->applyBelongsToDemoUsersScope($qC)
        )->delete();

        $this->applyBelongsToDemoUsersScope(Comment::query())->delete();

        // Update cache
        Comment::where('root_child_comments_cached_count', '>', 0)
            ->lazyById(100)
            ->each(function (Comment $comment) {
                $comment->root_child_comments_cached_count = Comment::whereRoot($comment->id)->count();
                $comment->save();
            });
    }

    protected function deleteAllSessions(): void
    {
        DB::table('sessions')->delete();
    }

    protected function allowToIssueUsedDemoUsers(): void
    {
        User::where('issued_for_demo', true)->update([
            'issued_for_demo' => false,
        ]);
    }
}
