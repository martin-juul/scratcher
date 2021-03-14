<?php
declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Genre;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Genre
 */
class GenreResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
        ];
    }
}
