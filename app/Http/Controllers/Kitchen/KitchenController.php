<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function queue()
    {
        $orders = Order::with(['table', 'items.menu'])
            ->whereIn('order_status', ['pending', 'cooking'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($orders);
    }

    public function startCooking(Order $order)
    {
        if ($order->order_status !== 'pending') {
            return response()->json([
                'message' => 'Pesanan tidak dalam status pending',
            ], 422);
        }

        $order->update(['order_status' => 'cooking']);

        return response()->json([
            'message' => 'Pesanan sedang dimasak',
            'order' => $order->load('items.menu'),
        ]);
    }

    public function finishCooking(Order $order)
    {
        if ($order->order_status !== 'cooking') {
            return response()->json([
                'message' => 'Pesanan tidak dalam status cooking',
            ], 422);
        }

        $order->update(['order_status' => 'ready']);

        return response()->json([
            'message' => 'Pesanan siap diantar',
            'order' => $order->load('items.menu'),
        ]);
    }

    public function history()
    {
        $orders = Order::with(['table', 'items.menu'])
            ->whereIn('order_status', ['ready', 'completed', 'cancelled'])
            ->orderBy('updated_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json($orders);
    }
}
