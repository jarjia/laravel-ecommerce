<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'owner_id',
        'quantity',
        'description',
        'type',
        'thumbnails',
        'price'
    ];

    protected $casts = ['thumbnail' => 'array'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'LIKE', '%' . $search . '%');
    }

    public static function filters($search, $sort, $isProfile)
    {
        $search = trim($search);

        $products = self::search($search)->select('price', 'name', 'id', 'quantity', 'thumbnails');

        if($isProfile) {
            $products->where('owner_id', auth()->user()->id);
        }

        if ($sort) {
            if (str_replace(' ', '_', strtolower($sort)) === 'old_to_new') {
                $products->oldest();
            } elseif (str_replace(' ', '_', strtolower($sort)) === 'new_to_old') {
                $products->latest();
            } elseif (str_replace(' ', '_', strtolower($sort)) === 'price_lower_to_higher') {
                $products->oldest('price');
            } elseif (str_replace(' ', '_', strtolower($sort)) === 'price_higher_to_lower') {
                $products->latest('price');
            }
        }

        return $products->get();
    }
}
