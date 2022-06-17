<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CharacterRoleResource extends JsonResource
{
    public static function collection($resource)
    {
        $resource->loadMissing([
            'character:id,full_name',
            'character.characterPoster.image',
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
            'description' => $this->description,
            'roleType' => $this->role_type,
            'character' => [
                'id' => $this->character->id,
                'fullName' => $this->character->full_name,
                'poster' => new ImageResource($this->character->characterPoster->image),
            ],
        ];
    }
}
