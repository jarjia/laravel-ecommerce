<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->resource->thumbnails = collect(json_decode($this->resource->thumbnails))->map(function ($img) {
            return asset('storage/' . $img);
        });

        return $this->resource->toArray();
    }
}
