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
                'likes' => Like::whereUser($this->id)->count(),
                'comments' => Comment::whereUser($this->id)->count(),
                'views' => View::whereUser($this->id)->count(),
                'stars' => 0,
            ],
        ];
    }
}
