<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartQuantityChangeRequest;
use App\Http\Requests\CreateCartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    public function create(CreateCartRequest $request): JsonResponse
    {
        $ids = $request->validated();
        $cart = Cart::with('product')->where('client_id', auth()->user()->id)->get();

        foreach($cart as $item) {
            if($item['product']['id'] === $ids['product_id']) {
                return response()->json('Product already in the cart', 403);
            }
        }

        Cart::create([
            ...$ids,
            'client_id' => auth()->user()->id,
            'quantity' => 1
        ]);

        return response()->json('cart item created', 201);
    }

    public function index(): JsonResponse
    {
        $cartItems = Cart::with('seller', 'client', 'product')
            ->where('client_id', auth()->user()->id)->get();

        $totalPrice = 0;
        $transformedCartItems = new CartResource($cartItems);

        foreach($cartItems as $item) {
            $totalPrice += $item['product']['price'] * $item['quantity'];
        }

        return response()->json(['main_data' => $transformedCartItems, 'total' => $totalPrice], 200);
    }

    public function destroy(Cart $cart): JsonResponse
    {
        $cart->delete();

        return response()->json('cart item removed', 200);
    }

    public function destroyAll(): JsonResponse
    {
        $cartItems = Cart::where('client_id', auth()->user()->id)->get();

        foreach($cartItems as $cart) {
            $cart->delete();
        }

        return response()->json('cart cleared', 200);
    }

    public function changeQuantity(Cart $cart, CartQuantityChangeRequest $request): JsonResponse
    {
        $data = $request->validated();

        if($cart->product['quantity'] < $data['quantity']) {
            return response()->json('not enough product in stock to fulfill request', 400);
        }

        $cart->update([
            'quantity' => $data['quantity']
        ]);

        return response()->json('quantity changed', 200);
    }
}
