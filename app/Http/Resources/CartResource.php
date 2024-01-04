<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->map(function ($cart) {
            $cart['mainImage'] = asset('storage/' . json_decode($cart['product']['thumbnails'])[0]);
            $cart['product_name'] = $cart['product']['name'];
            $cart['product_price'] = $cart['product']['price'];
            $cart['product_quantity'] = $cart['product']['quantity'];
            $cart['product_desc'] = $cart['product']['description'];
            $cart['product_type'] = $cart['product']['type'];

            unset($cart['product']);
            unset($cart['seller']);
            unset($cart['client']);

            return $cart;
        })->toArray();
    }
}
