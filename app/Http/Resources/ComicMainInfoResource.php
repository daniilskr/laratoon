<?php

namespace App\Http\Resources;

use App\Enums\CharacterRoleType;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\ViewsService;

class ComicMainInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        /** @var ViewsService */
        $viewsService = resolve(ViewsService::class);

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'author' => [
                'id' => $this->author->id,
                'fullName' => $this->author->full_name,
            ],
            'title' => $this->title,
            'description' => $this->description,
            'statistics' => [
                'likes' => [
                    'total' => $this->likeable->likes_cached_count,
                ],
                'views' => [
                    'total' => $viewsService->getTotalForComic($this->id),
                ],
                'comments' => [
                    'total' => $this->commentable->comments_cached_count,
                ],
            ],
            'comicPoster' => new ImageResource($this->comicPoster->image),
            'comicHeaderBackground' => new ImageResource($this->comicHeaderBackground->image),
            'commentable' => [
                'id' => $this->commentable->id,
            ],
            $this->merge($this->when(
                $request->user(),
                fn () => ([
                    'cachedLatestViewedEpisode' => new EpisodeResource($this->cacheLatestViewedEpisodeByRequestUser?->episode),
                    'comicUserListSlug' => $this->comicUserListEntries()->whereBelongsTo($request->user())->first()?->comicUserList?->slug,
                ]),
                [
                    'cachedLatestViewedEpisode' => null,
                    'comicUserListSlug' => null,
                ]
            )),
            'episodes' => EpisodeResource::collection(
                $this->episodes()
                    ->orderByDesc('id')
                    ->limit(5)->get()
            ),
            'tags' => ComicTagResource::collection($this->comicTags),
            'mainCharacters' => CharacterRoleResource::collection(
                $this->characterRoles()
                    ->where('role_type', CharacterRoleType::Main)
                    ->get()
            ),
            'otherComicsByAuthor' => ComicCardResource::collection($this->author->comics()->where('id', '<>', $this->id)->get()),
        ];
    }
}
