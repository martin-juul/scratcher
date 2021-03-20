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
            'name'      => $this->name,
            'isPublic'  => $this->is_public,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'tracks'    => $this->whenLoaded('tracks'),
            'user'      => $this->whenLoaded('user'),
        ];
    }
}
