<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use Illuminate\Http\Request;

class CashierController extends Controller
{
    public function tables()
    {
        $tables = Table::withCount(['orders' => function ($q) {
            $q->where('order_status', '!=', 'completed');
        }])->get();

        return response()->json($tables);
    }

    public function tableOrders(Table $table)
    {
        $orders = $table->orders()
            ->with('items.menu')
            ->where('order_status', '!=', 'completed')
            ->get();

        return response()->json([
            'table' => $table,
            'orders' => $orders,
        ]);
    }

    public function menus()
    {
        return response()->json(
            Menu::with('category', 'modifiers')->where('is_available', true)->get()
        );
    }

    public function createOrder(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string',
        ]);

        $table = Table::findOrFail($request->table_id);
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
            'message' => 'Pesanan berhasil dibuat',
            'order' => $order->load('items.menu'),
        ], 201);
    }

    public function confirmCashPayment(Order $order)
    {
        if ($order->payment_status === 'paid') {
            return response()->json([
                'message' => 'Pesanan ini sudah dibayar',
            ], 422);
        }

        $order->update([
            'payment_status' => 'paid',
            'payment_method' => 'cash',
        ]);

        $order->table->update(['status' => 'empty']);

        return response()->json([
            'message' => 'Pembayaran tunai dikonfirmasi',
            'order' => $order,
        ]);
    }

    public function completeOrder(Order $order)
    {
        if ($order->order_status !== 'ready') {
            return response()->json([
                'message' => 'Pesanan belum siap',
            ], 422);
        }

        $order->update(['order_status' => 'completed']);

        $hasActiveOrders = $order->table->orders()
            ->where('id', '!=', $order->id)
            ->where('order_status', '!=', 'completed')
            ->exists();

        if (!$hasActiveOrders) {
            $order->table->update(['status' => 'empty']);
        }

        return response()->json([
            'message' => 'Pesanan selesai',
            'order' => $order,
        ]);
    }
}
