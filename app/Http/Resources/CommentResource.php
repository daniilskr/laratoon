<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public static function collection($resource)
    {
        $resource->loadMissing([
            'user.userAvatar.image',
            'likeable',
        ]);

        if ($user = request()->user()) {
            $resource->loadMissing([
                'likeable.likes' => fn ($q) => $q->whereUser($user) 
            ]);
        }

        return parent::collection($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'publishedAt' => $this->created_at,
            'text' => $this->comment_text,
            'rootChildCommentsCachedCount' => $this->root_child_comments_cached_count,

            'author' => [
                'id' => $this->user->id,
                'fullName' => $this->user->name,
                'avatar' => new ImageResource($this->user->userAvatar->image),
            ],

            'likeable' => [
                'id' => $this->likeable->id,
                'likesCachedCount' => $this->likeable->likes_cached_count,
                'isLikedByUser' => ! is_null($this->likeable->getUserLike(request()->user())),
            ],

            'commentable' => [
                'id' => $this->commentable_id,
            ],

            'rootCommentId' => $this->root_comment_id,
            'parentCommentId' => $this->parent_comment_id,
        ];
    }
}
