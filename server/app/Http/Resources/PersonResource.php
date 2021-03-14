<?php
declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Person;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Person
 */
class PersonResource extends JsonResource
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
            'name'        => $this->name,
            'description' => $this->description,
            $this->mergeWhen(isset($this->pivot->role), [
                'role' => $this->pivot->role,
            ]),
            'created'     => $this->created_at,
            'updated'     => $this->updated_at,
        ];
    }
}
