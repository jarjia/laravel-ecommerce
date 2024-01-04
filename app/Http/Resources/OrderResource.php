<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->map(function ($order) {
            $order['price'] = $order['product']['price'];
            $order['name'] = $order['product']['name'];
            $order['mainImage'] = asset('storage/' . json_decode($order['product']['thumbnails'])[0]);

            unset($order['product']);

            return $order;
        })->toArray();
    }
}
