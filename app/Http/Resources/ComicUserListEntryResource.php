<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ComicUserListEntryResource extends JsonResource
{
    public static function collection($resource)
    {
        $resource->loadMissing([
            'comic.author',
            'comic.comicPoster.image',
            'comic.latestEpisode',
            'comic.cacheLatestViewedEpisodeByRequestUser.episode',
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
            'comic' => [
                'id' => $this->comic->id,
                'title' => $this->comic->title,
                'slug' => $this->comic->slug,
                'episodesLeft' => ($this->comic->latestEpisode?->number ?? 0) - ($this->comic->cacheLatestViewedEpisodeByRequestUser?->episode?->number ?? 0),
                
                'cachedLatestViewedEpisode' => new EpisodeResource($this->comic->cacheLatestViewedEpisodeByRequestUser?->episode),
                'comicPoster' => new ImageResource($this->comic->comicPoster->image),
                'author' => [
                    'id' => $this->comic->author->id,
                    'fullName' => $this->comic->author->full_name,
                ],
            ],
        ];
    }
}
