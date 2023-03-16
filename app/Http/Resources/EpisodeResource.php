<?php

namespace App\Http\Resources;

use App\Services\ViewableViewsByUsersRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class EpisodeResource extends JsonResource
{
    public static function collection($resource)
    {
        $resource->loadMissing([
            'episodePoster.image',
            'viewable',
        ]);

        if ($resource->isNotEmpty()) {
            /** @var ViewableViewsByUsersRepository */
            $repository = app(ViewableViewsByUsersRepository::class);
            $repository->loadForUserAndViewables(request()->user(), $resource->pluck('viewable'));
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
        /** @var ViewableViewsByUsersRepository */
        $viewsRepository = app(ViewableViewsByUsersRepository::class);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'number' => $this->number,
            'publishedAt' => $this->created_at,
            'poster' => new ImageResource($this->whenLoaded('episodePoster', fn () => $this->episodePoster->image)),
            'viewable' => $this->whenLoaded('viewable', fn () => [
                'viewsCachedCount' => $this->viewable->views_cached_count,
                'isSeenByUser' => $viewsRepository->getForUserAndViewable($request->user(), $this->viewable) ? true : false,
            ]),
        ];
    }
}
