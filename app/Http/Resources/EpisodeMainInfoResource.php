<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EpisodeMainInfoResource extends JsonResource
{
    public static function collection($resource)
    {
        $resource->loadMissing(['episodePages.image']);

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
            'title' => $this->title,
            'number' => $this->number,
            'comic' => [
                'id' => $this->comic->id,
                'title' => $this->comic->title,
                'slug' => $this->comic->slug,
            ],
            'commentable' => [
                'id' => $this->commentable->id,
            ],
            'pages' => $this->episodePages->map(fn ($page) => [
                'id' => $page->id,
                'order' => $page->order,
                'image' => new ImageResource($page->image),
            ]),
        ];
    }
}
