<?php

namespace App\DTOs;

class OrderDTO
{
    public function __construct(
        public readonly int $user_id,
        public readonly string $type,
        public readonly float $weight,
        public readonly float $price_per_gram
    ) {}
}
