<?php

namespace App\Services\Demo;

use App\Models\Comment;
use App\Models\Likeable;
use App\Scopes\DoesNotBelongToOtherDemoUsersScope;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\DB;

class DemoDataCleaner
{
    public function __construct(
        protected DemoUsersPool $pool,
    ) {
    }

    public function cleanUp(): void
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
                fn ($qU) => $qU->whereBetween('id', $this->pool->getDemoUserIdsRange())
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
        $this->pool->returnAll();
    }
}
