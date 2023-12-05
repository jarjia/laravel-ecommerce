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
            $productImage = asset('storage/' . $product->mainImage);
            $product['mainImage'] = $productImage;
            $product['thumbnails'] = collect(json_decode($product['thumbnails']))->map(function ($img) {
                return asset('storage/' . $img);
            });

            return $product;
        })->toArray();
    }
}
