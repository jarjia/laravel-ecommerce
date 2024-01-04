<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductEditRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductsResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function create(ProductCreateRequest $request): JsonResponse
    {
        $attrs = $request->validated();
        $imgs = [];

        foreach (request()->file('images') as $img) {
            $file = $img->store('assets', 'public');
            array_push($imgs, $file);
        }

        unset($attrs['images']);

        $attrs['thumbnails'] = json_encode($imgs);
        $attrs['owner_id'] = auth()->user()->id;

        Product::create($attrs);

        return response()->json('product was created', 201);
    }

    public function index(FilterRequest $request): JsonResponse
    {
        $attrs = $request->validated();

        $products = Product::filters($attrs['search'], $attrs['sort'] ?? null, false, $attrs['pageParam'] ?? null);

        $transformedProducts = new ProductsResource($products['products']);

        return response()->json(['products' => $transformedProducts, 'all_pages' => $products['count'],], 201);
    }

    public function indexUser(FilterRequest $request): JsonResponse
    {
        $attrs = $request->validated();

        $products = Product::filters($attrs['search'], $attrs['sort'] ?? null, true, $attrs['pageParam'] ?? null);

        $transformedProducts = new ProductsResource($products['products']);

        return response()->json(['products' => $transformedProducts, 'all_pages' => $products['count'],], 201);
    }

    public function show(Product $productId): JsonResponse
    {
        $transformedProducts = new ProductResource($productId);

        return response()->json($transformedProducts, 200);
    }

    public function update(Product $productId, ProductEditRequest $request): JsonResponse
    {
        $this->authorize('accessProduct', $productId);

        $attrs = $request->validated();
        $imgs = [];
        $o = [];
        $imenaImgs = [];

        if(request()->file('files')) {
            foreach(request()->file('files') as $index => $img) {
                $file = $img['file']->store('assets', 'public');
                array_splice($imgs, intval($attrs['files'][$index]['order']), 0, $file);
            }
        }

        if(isset($attrs['images'])) {
            foreach($attrs['images'] as $img) {
                $cutImage = explode('storage/', $img['image'])[1];
                array_push($imenaImgs, $cutImage);
            }

            foreach($attrs['images'] as $img) {
                $cutImage = explode('storage/', $img['image'])[1];
                foreach(json_decode($productId['thumbnails']) as $oldImg) {
                    if(!in_array($oldImg, $imenaImgs)) {
                        Storage::disk('public')->delete($oldImg);
                    }
                }
                array_splice($imgs, intval($img['order']), 0, $cutImage);
            }
        } else {
            foreach(json_decode($productId['thumbnails']) as $oldImg) {
                Storage::disk('public')->delete($oldImg);
            }
        }

        unset($attrs['images']);
        unset($attrs['files']);

        $attrs['thumbnails'] = json_encode($imgs);

        $productId->update($attrs);

        return response()->json($o, 200);
    }

    public function destroy(Product $productId): JsonResponse
    {
        $this->authorize('accessProduct', $productId);

        $productId->delete();

        foreach(json_decode($productId['thumbnails']) as $oldImg) {
            Storage::disk('public')->delete($oldImg);
        }

        return response()->json('product deleted', 200);
    }
}
