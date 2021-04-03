<?php
declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Track;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Track
 */
class TrackResource extends JsonResource
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
            'title'        => $this->title,
            'sha256'       => $this->sha256,
            'path'         => $this->path,
            'file_format'  => $this->file_format,
            'file_size'    => $this->file_size,
            'mime_type'    => $this->mime_type,
            'isrc'         => $this->isrc,
            'bitrate'      => $this->bitrate,
            'length'       => $this->length,
            'track_number' => $this->track_number,
            'stream'       => route('tracks.stream', ['track' => $this->sha256]),
            'self'         => route('albums.tracks.show', ['album' => $this->album->slug, 'track' => $this->sha256]),
            'created'      => $this->created_at,
            'updated'      => $this->updated_at,
            $this->mergeWhen(isset($this->album->artwork), [
                'artwork' => ArtworkResource::make($this->album->artwork),
            ]),
            'genres'       => $this->whenLoaded('genres', GenreResource::collection($this->genres)),
            'artists'      => $this->whenLoaded('artists', PersonResource::collection($this->artists)),
        ];
    }
}
