<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(): JsonResponse
    {
        $orders = Order::with('product')->where('to_user', auth()->user()->id)->get();

        $transformedOrders = new OrderResource($orders);

        return response()->json($transformedOrders, 200);
    }

    public function destroy(Order $order): JsonResponse
    {
        $product = Product::find($order->product_id);

        $product->update(['quantity' => $product->quantity + $order->quantity]);

        $order->delete();

        return response()->json($product, 200);
    }

}
