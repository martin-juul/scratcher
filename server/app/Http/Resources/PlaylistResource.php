<?php
declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Playlist;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Playlist
 */
class PlaylistResource extends JsonResource
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
            'name'       => $this->name,
            'slug'       => $this->slug,
            'isPublic'   => $this->is_public,
            'created'    => $this->created_at,
            'updated'    => $this->updated_at,
            'tracks'     => TrackResource::collection($this->whenLoaded('tracks')),
            'trackCount' => $this->tracks_count,
            'user'       => UserResource::make($this->whenLoaded('user')),
        ];
    }
}
