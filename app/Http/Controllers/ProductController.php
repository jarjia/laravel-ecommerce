<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductsResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function test(): JsonResponse
    {
        $data = [
            'name' => 'poco',
            'type' => 'electronical',
            'owner_id' => 1,
            'price' => 100,
            'quantity' => 9,
            'description' => 'this phone good',
            'thumbnails' => ['jarji', 'abua', 'okay'],
            'mainImage' => 'assets/logo.png'
        ];

        $data['thumbnails'] = json_encode($data['thumbnails']);

        Product::create($data);

        return response()->json(['data' => $data]);
    }

    public function index(): JsonResponse
    {
        $products = Product::select('price', 'name', 'id', 'mainImage', 'quantity')->get();

        $transformedProducts = new ProductsResource($products);

        return response()->json($transformedProducts);
    }
}
