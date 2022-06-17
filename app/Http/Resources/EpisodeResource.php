<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EpisodeResource extends JsonResource
{
    public static function collection($resource)
    {
        $resource->loadMissing([
            'episodePoster.image',
            'viewable.viewOfRequestUser',
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
            'title' => $this->title,
            'number' => $this->number,
            'publishedAt' => $this->created_at,
            'poster' => new ImageResource($this->whenLoaded('episodePoster', fn () => $this->episodePoster->image)),
            'viewable' => $this->whenLoaded('viewable', fn () => [
                'viewsCachedCount' => $this->viewable->views_cached_count,
                'isSeenByUser' => $this->viewable->viewOfRequestUser ? true : false,
            ]),
        ];
    }
}
