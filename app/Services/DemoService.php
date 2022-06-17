<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\User;
use App\Scopes\DoesNotBelongToOtherDemoUsersScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DemoService
{
    public const DEMO_USERS_MIN_ID = 1000;

    public const DEMO_USERS_MAX_ID = 2000;

    public function getDemoUserIdsRange()
    {
        return [static::DEMO_USERS_MIN_ID, static::DEMO_USERS_MAX_ID];
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

    public function cleanUpDemoUserData()
    {
        $this->cleanUpComments();
        $this->deleteAllSessions();
        $this->allowToIssueUsedDemoUsers();
    }

    protected function cleanUpComments()
    {
        Comment::withoutGlobalScope(DoesNotBelongToOtherDemoUsersScope::class)
            ->whereHas(
                'user',
                fn ($qU) => $qU->whereBetween('id', $this->getDemoUserIdsRange())
            )->delete();

        // Update cache
        Comment::where('root_child_comments_cached_count', '>', 0)
            ->lazyById(100)
            ->each(function (Comment $comment) {
                $comment->root_child_comments_cached_count = Comment::whereRoot($comment->id)->count();
                $comment->save();
            });
    }

    protected function deleteAllSessions()
    {
        DB::table('sessions')->delete();
    }

    protected function allowToIssueUsedDemoUsers()
    {
        User::where('issued_for_demo', true)->update([
            'issued_for_demo' => false,
        ]);
    }
}
