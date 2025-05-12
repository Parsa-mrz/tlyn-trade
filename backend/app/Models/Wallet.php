<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wallet extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'gold_balance',
        'rial_balance',
    ];

    /**
     * Get the user associated with the wallet.
     *
     * This relationship defines the user to whom the wallet belongs.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
