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
        ]);

        if ($user = request()->user()) {
            $resource->loadMissing([
                'comic.cachedLatestViewedEpisodeByUsers' => fn ($q) => $q->whereUser($user),
            ]);

            $resource->loadMissing([
                'comic.cachedLatestViewedEpisodeByUsers.episode',
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
        $cachedLatestViewedEpisode = $this->comic->getCachedLatestViewedEpisodeByUser($request->user());

        return [
            'id' => $this->id,
            'comic' => [
                'id' => $this->comic->id,
                'title' => $this->comic->title,
                'slug' => $this->comic->slug,
                'episodesLeft' => ($this->comic->latestEpisode->number ?? 0) - ($cachedLatestViewedEpisode?->episode->number ?? 0),

                'cachedLatestViewedEpisode' => new EpisodeResource(
                    $cachedLatestViewedEpisode?->episode,
                ),
                'comicPoster' => new ImageResource($this->comic->comicPoster->image),
                'author' => [
                    'id' => $this->comic->author->id,
                    'fullName' => $this->comic->author->full_name,
                ],
            ],
        ];
    }
}
