<?php

namespace App\Http\Resources;

use App\Models\Character;
use App\Models\Comic;
use App\Models\Contracts\HasCommentable;
use App\Models\Episode;
use Illuminate\Http\Resources\Json\JsonResource;

class UserCommentResource extends JsonResource
{
    protected function getCommentableOwnerLabel(HasCommentable $hasCommentable)
    {
        $label = match ($hasCommentable::class) {
            Comic::class => $hasCommentable->title,
            Character::class => $hasCommentable->full_name,
            Episode::class => "{$hasCommentable->comic->title} #{$hasCommentable->number}",
            default => '?',
        };

        return $label;
    }

    public static function collection($resource)
    {
        $resource->loadMissing([
            'commentable.owner',
            'likeable',
        ]);

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

            'commentable' => [
                'id' => $this->commentable->id,
                'owner' => [
                    'id' => $this->commentable->owner->id,
                    'label' => $this->getCommentableOwnerLabel($this->commentable->owner),
                ],
            ],

            'likeable' => [
                'id' => $this->likeable->id,
                'likesCachedCount' => $this->likeable->likes_cached_count,
            ],
        ];
    }
}
