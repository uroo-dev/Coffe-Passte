<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Modifier;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ServiceRequest;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function menu($qrToken)
    {
        $table = Table::where('qr_token', $qrToken)->firstOrFail();
        $categories = Category::with(['menus' => function ($q) {
            $q->where('is_available', true);
        }, 'menus.modifiers'])->get();

        return response()->json([
            'table' => $table,
            'categories' => $categories,
        ]);
    }

    public function modifiers(Menu $menu)
    {
        return response()->json(
            $menu->modifiers()->where('is_available', true)->get()
        );
    }

    public function checkout(Request $request, $qrToken)
    {
        $table = Table::where('qr_token', $qrToken)->firstOrFail();

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string',
        ]);

        $total = 0;
        $orderItems = [];

        foreach ($request->items as $item) {
            $menu = Menu::findOrFail($item['menu_id']);
            $price = $menu->price;
            $total += $price * $item['quantity'];

            $orderItems[] = [
                'menu_id' => $menu->id,
                'quantity' => $item['quantity'],
                'notes' => $item['notes'] ?? null,
                'price_at_order' => $price,
            ];
        }

        $order = Order::create([
            'table_id' => $table->id,
            'order_reference' => 'SR-' . now()->format('Ymd') . '-' . str_pad((string) Order::whereDate('created_at', today())->count() + 1, 3, '0', STR_PAD_LEFT),
            'total_amount' => $total,
            'order_status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        foreach ($orderItems as &$item) {
            $item['order_id'] = $order->id;
        }
        OrderItem::insert($orderItems);

        $table->update(['status' => 'occupied']);

        return response()->json([
            'message' => 'Pesanan berhasil dikirim',
            'order' => $order->load('items.menu'),
        ], 201);
    }

    public function trackOrder(Request $request, Order $order)
    {
        $order->load('items.menu');

        if ($request->expectsJson()) {
            return response()->json([
                'order_reference' => $order->order_reference,
                'order_status' => $order->order_status,
                'payment_status' => $order->payment_status,
                'total_amount' => $order->total_amount,
                'items' => $order->items,
                'created_at' => $order->created_at,
            ]);
        }

        return view('customer.order-tracking', compact('order'));
    }

    public function pay(Request $request, Order $order)
    {
        $request->validate([
            'payment_method' => 'required|in:qris,e_wallet',
        ]);

        $order->update([
            'payment_status' => 'paid',
            'payment_method' => $request->payment_method,
        ]);

        $order->table->update(['status' => 'waiting_payment']);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Pembayaran berhasil',
                'order' => $order,
            ]);
        }

        return redirect()->route('customer.invoice', $order);
    }

    public function callCashier(Request $request, $qrToken)
    {
        $table = Table::where('qr_token', $qrToken)->firstOrFail();

        $table->update(['status' => 'waiting_payment']);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Kasir telah dipanggil',
                'table' => $table,
            ]);
        }

        return redirect()->back()->with('success', 'Kasir telah dipanggil!');
    }

    public function callStaff(Request $request, $qrToken)
    {
        $table = Table::where('qr_token', $qrToken)->firstOrFail();

        $request->validate([
            'type' => 'required|string',
            'label' => 'required|string',
        ]);

        ServiceRequest::create([
            'table_id' => $table->id,
            'type' => $request->type,
            'label' => $request->label,
        ]);

        return response()->json([
            'message' => 'Permintaan terkirim',
            'request' => [
                'type' => $request->type,
                'label' => $request->label,
            ],
        ]);
    }

    public function paymentStatus(Order $order)
    {
        return response()->json([
            'payment_status' => $order->payment_status,
            'order_status' => $order->order_status,
        ]);
    }
}
