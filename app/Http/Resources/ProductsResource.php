<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->map(function ($product) {
            if($product['quantity'] === 0) {
                $product['isInStock'] = false;
            } else {
                $product['isInStock'] = true;
            }
            $product['thumbnails'] = collect(json_decode($product['thumbnails']))->map(function ($img) {
                return asset('storage/' . $img);
            });
            $product['mainImage'] = json_decode($product['thumbnails'])[0];

            return $product;
        })->toArray();
    }
}
