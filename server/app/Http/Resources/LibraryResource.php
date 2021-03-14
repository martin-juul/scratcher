<?php
declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Library;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Library
 */
class LibraryResource extends JsonResource
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
            'name'    => (string)$this->name,
            'path'    => (string)$this->path,
            'created' => $this->created_at,
            'updated' => $this->updated_at,
        ];
    }
}
