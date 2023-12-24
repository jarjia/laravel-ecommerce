<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct() {}

    public function accessProduct(User $user, Product $product)
    {
        return $user->id === $product->owner_id;
    }
}
