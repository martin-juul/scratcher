<?php
declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Artwork;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Artwork
 */
class ArtworkResource extends JsonResource
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
            'basename' => $this->basename,
            'mime'     => $this->mime,
            'size'     => $this->size,
            'height'   => $this->height,
            'width'    => $this->width,
            'url'      => $this->getPath(),
            'created'  => $this->created_at,
            'updated'  => $this->updated_at,
        ];
    }
}
