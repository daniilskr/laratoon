<?php

namespace App\Http\Resources;

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
            // TODO: this part is unused right now, but if it will
            // be used we have to gather actual stats there
            'statistics' => [
                'likes' => 0,
                'comments' => 0,
                'views' => 0,
                'stars' => 0,
            ],
        ];
    }
}
