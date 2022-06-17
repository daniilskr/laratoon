<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ComicCardResource extends JsonResource
{
    public static function collection($resource)
    {
        $resource->loadMissing([
            'author',
            'comicPoster.image',
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
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'author' => [
                'id' => $this->author->id,
                'fullName' => $this->author->full_name,
            ],
            'statistics' => [
                'likes' => [
                    'total' => $this->likeable->likes_cached_count,
                ],
            ],
            'comicPoster' => new ImageResource($this->comicPoster->image),
        ];
    }
}
