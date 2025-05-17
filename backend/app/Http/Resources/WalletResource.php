<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class WalletResource
 *
 * Transforms wallet data for JSON responses.
 *
 * @package App\Http\Resources
 *
 */
class WalletResource extends JsonResource
{
    /**
     * Transform the wallet resource into an array suitable for JSON output.
     *
     * @param Request $request  The incoming request instance.
     *
     * @return array<string, mixed>  The transformed wallet data.
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => $this->whenLoaded('user'),
            'gold_balance' => $this->resource->gold_balance,
            'rial_balance' => number_format($this->resource->rial_balance),
        ];
    }
}
