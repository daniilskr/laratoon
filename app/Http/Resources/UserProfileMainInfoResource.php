<?php

namespace App\Http\Resources;

use App\Models\Comment;
use App\Models\Like;
use App\Models\View;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileMainInfoResource extends JsonResource
{
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
            'name' => $this->name,
            'comicUserLists' => ComicUserListResource::collection($this->comicUserLists),
            'avatar' => new ImageResource($this->userAvatar->image),
            'statistics' => [
                'likes' => $this->likes_cached_count,
                'comments' => $this->comments_cached_count,
                'views' => $this->views_cached_count,
                'stars' => $this->stars_cached_count,
            ],
        ];
    }
}
