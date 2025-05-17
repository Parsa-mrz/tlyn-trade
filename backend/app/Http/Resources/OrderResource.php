<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class OrderResource
 *
 * Transforms the Order model into a JSON-serializable structure for API responses.
 *
 * @package App\Http\Resources
 *
 */
class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request The incoming HTTP request instance.
     * @return array<string, mixed> The array representation of the order for the API response.
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => $this->whenLoaded('user'),
            'type' => $this->resource->type,
            'weight' => $this->resource->weight,
            'price_per_gram' => $this->resource->price_per_gram,
            'remaining_weight' => $this->resource->remaining_weight,
            'status' => $this->resource->status,
        ];
    }
}
