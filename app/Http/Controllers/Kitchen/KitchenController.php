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

        if (request()->expectsJson()) {
            return response()->json($orders);
        }

        return view('staff.kitchen', compact('orders'));
    }

    public function startCooking(Order $order)
    {
        if ($order->order_status !== 'pending') {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Pesanan tidak dalam status pending'], 422);
            }
            return redirect()->back()->with('error', 'Pesanan tidak dalam status pending');
        }

        $order->update(['order_status' => 'cooking']);

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Pesanan sedang dimasak', 'order' => $order->load('items.menu')]);
        }

        return redirect()->back()->with('success', 'Pesanan sedang dimasak');
    }

    public function finishCooking(Order $order)
    {
        if ($order->order_status !== 'cooking') {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Pesanan tidak dalam status cooking'], 422);
            }
            return redirect()->back()->with('error', 'Pesanan tidak dalam status cooking');
        }

        $order->update(['order_status' => 'completed']);

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Pesanan selesai', 'order' => $order->load('items.menu')]);
        }

        return redirect()->back()->with('success', 'Pesanan selesai');
    }

    public function history()
    {
        $orders = Order::with(['table', 'items.menu'])
            ->whereIn('order_status', ['completed', 'cancelled'])
            ->orderBy('updated_at', 'desc')
            ->limit(50)
            ->get();

        if (request()->expectsJson()) {
            return response()->json($orders);
        }

        return view('staff.kitchen-history', compact('orders'));
    }
}
