<?php
declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\PersonalAccessToken;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin PersonalAccessToken
 */
class PersonalAccessTokenResource extends JsonResource
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
            'id'        => $this->id,
            'name'      => $this->name,
            'abilities' => $this->abilities,
            'created'   => $this->created_at,
            'updated'   => $this->updated_at,
        ];
    }
}
