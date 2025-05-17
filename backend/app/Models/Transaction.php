<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'buyer_id',
        'seller_id',
        'order_id',
        'match_order_id',
        'weight',
        'price_per_gram',
        'total_price',
        'commission',
    ];

    /**
     * Get the buyer associated with the transaction.
     *
     * @return BelongsTo<User, Transaction>
     */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * Get the seller associated with the transaction.
     *
     * @return BelongsTo<User, Transaction>
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Get the original order associated with the transaction.
     *
     * @return BelongsTo<Order, Transaction>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Get the matching order that was fulfilled in the transaction.
     *
     * @return BelongsTo<Order, Transaction>
     */
    public function matchOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'match_order_id');
    }
}
