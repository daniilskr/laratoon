<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Services\CachedLatestViewedEpisodesRepository;
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
            /** @var CachedLatestViewedEpisodesRepository */
            $repository = app(CachedLatestViewedEpisodesRepository::class);
            $repository->loadForUserAndComics($user, $resource->pluck('comic'));
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
        /** @var CachedLatestViewedEpisodesRepository */
        $repository = app(CachedLatestViewedEpisodesRepository::class);

        $cachedLatestViewedEpisode = ($user = $request->user())
                                        ? $repository->getForUserAndComic($user, $this->comic)
                                        : null;

        return [
            'id' => $this->id,
            'comic' => [
                'id' => $this->comic->id,
                'title' => $this->comic->title,
                'slug' => $this->comic->slug,
                'episodesLeft' => ($this->comic->latestEpisode->number ?? 0) - ($cachedLatestViewedEpisode?->episode->number ?? 0),

                'cachedLatestViewedEpisode' => new EpisodeResource(
                    // TODO: Maybe it is better to show for owner of the comicUserList instead of current user?
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
