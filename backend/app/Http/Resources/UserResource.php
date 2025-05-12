<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 *
 * Transforms a User model into a JSON representation.
 *
 *
 * @property-read int $id
 * @property-read string $name
 * @property-read string $email
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * This method is called when converting the resource to JSON.
     *
     * @param  \Illuminate\Http\Request  $request  The current HTTP request instance.
     * @return array<string, mixed> An array representation of the User resource.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'email' => $this->resource->email,
        ];
    }
}
