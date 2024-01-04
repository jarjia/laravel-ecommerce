<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartQuantityChangeRequest;
use App\Http\Requests\CartRequest;
use App\Http\Requests\CreateCartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use PhpCsFixer\Console\Report\FixReport\JsonReporter;

class CartController extends Controller
{
    public function create(CreateCartRequest $request): JsonResponse
    {
        $ids = $request->validated();
        $cart = Cart::with('product')->where('client_id', auth()->user()->id)->get();
        $product = Product::find($ids['product_id']);

        if($product['quantity'] < 1) {
            return response()->json('Product is out of stock', 400);
        }

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
        $totalPriceWithShippingCosts = 0;
        $transformedCartItems = new CartResource($cartItems);

        foreach($cartItems as $item) {
            $totalPrice += $item['product']['price'] * $item['quantity'];
            $totalPriceWithShippingCosts += $item['product']['price'] * $item['quantity'] + $item['quantity'] * 20;
        }

        return response()->json([
            'main_data' => $transformedCartItems, 
            'total' => $totalPrice, 
            'total_with_shipping_cost' => $totalPriceWithShippingCosts
        ], 200);
    }

    public function indexCount(): JsonResponse
    {
        $cartCount = Cart::where('client_id', auth()->user()->id)->get();

        return response()->json(count($cartCount), 200);
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

    public function checkoutProducts(): JsonResponse
    {
        $cartItems = Cart::where('client_id', auth()->user()->id)->get();

        foreach($cartItems as $cart) {
            $product = Product::find($cart['product_id']);

            if($cart['quantity'] > $product['quantity']) {
                return response()->json('Not enough products to fulfill checkout', 422);
            } else {
                $product->update([
                    'quantity' => $product['quantity'] - $cart['quantity'],
                ]);

                Order::create([
                    'to_user' => auth()->user()->id,
                    'from_user' => $product['owner_id'],
                    'product_id' => $product['id'],
                    'arrives_at' => now()->addMinutes(2),
                    'quantity' => $cart['quantity']
                ]);
            }

            $cart->delete();
        }

        return response()->json($cartItems, 201);
    }

}
