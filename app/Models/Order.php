<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'to_user',
        'from_user',
        'product_id',
        'arrives_at',
        'quantity'
    ];

    public function from_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user');
    }

    public function to_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
