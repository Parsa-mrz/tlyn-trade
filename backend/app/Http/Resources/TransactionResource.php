<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class TransactionResource
 *
 * @package App\Http\Resources
 *
 */
class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request The current HTTP request instance.
     * @return array<string, mixed> An associative array containing transaction data to be returned in the JSON response.
     */
    public function toArray(Request $request): array
    {
        return [
            'weight' => $this->resource->weight,
            'price_per_gram' => $this->resource->price_per_gram,
            'total_price' => $this->resource->total_price,
            'commission' => $this->resource->commission,
            'date' => $this->resource->created_at->format('Y-m-d H:i'),
        ];
    }
}
