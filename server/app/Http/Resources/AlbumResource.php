<?php
declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Album;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Album
 */
class AlbumResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'title'       => $this->title,
            'year'        => $this->year,
            'slug'        => $this->slug,
            'description' => $this->description,
            'created'     => $this->created_at,
            'updated'     => $this->updated_at,
            $this->mergeWhen($this->relationLoaded('artist'), [
                'artist' => new PersonResource($this->artist[0]),
            ]),
            $this->mergeWhen($this->relationLoaded('artwork'), [
                'artwork' => new ArtworkResource($this->artwork),
            ]),
            $this->mergeWhen($this->relationLoaded('tracks'), [
                'tracks' => TrackResource::collection($this->tracks),
            ]),
        ];
    }
}
