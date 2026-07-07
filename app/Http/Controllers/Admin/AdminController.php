<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Modifier;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $today = today();
        $startOfMonth = now()->startOfMonth();

        $todayRevenue = Order::whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $monthRevenue = Order::where('created_at', '>=', $startOfMonth)
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $totalOrders = Order::whereDate('created_at', $today)->count();
        $activeOrders = Order::whereIn('order_status', ['pending', 'cooking'])->count();
        $occupiedTables = Table::where('status', 'occupied')->count();

        $topMenus = OrderItem::selectRaw('menu_id, SUM(quantity) as total_qty')
            ->with('menu')
            ->groupBy('menu_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $hourlyOrders = Order::selectRaw('HOUR(created_at) as hour, COUNT(*) as total')
            ->whereDate('created_at', $today)
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        return response()->json([
            'today_revenue' => (float) $todayRevenue,
            'month_revenue' => (float) $monthRevenue,
            'total_orders_today' => $totalOrders,
            'active_orders' => $activeOrders,
            'occupied_tables' => $occupiedTables,
            'top_menus' => $topMenus,
            'hourly_orders' => $hourlyOrders,
        ]);
    }

    public function tables()
    {
        return response()->json(Table::withCount('orders')->get());
    }

    public function generateQr(Table $table)
    {
        $table->update(['qr_token' => Str::random(40)]);

        return response()->json([
            'message' => 'QR token berhasil diperbarui',
            'table' => $table,
        ]);
    }

    public function storeTable(Request $request)
    {
        $request->validate([
            'table_number' => 'required|string|max:10|unique:tables,table_number',
        ]);

        $table = Table::create([
            'table_number' => $request->table_number,
            'qr_token' => Str::random(40),
            'status' => 'empty',
        ]);

        return response()->json($table, 201);
    }

    public function destroyTable(Table $table)
    {
        $table->delete();
        return response()->json(['message' => 'Meja berhasil dihapus']);
    }

    public function categories()
    {
        return response()->json(Category::withCount('menus')->get());
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return response()->json($category, 201);
    }

    public function updateCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return response()->json($category);
    }

    public function destroyCategory(Category $category)
    {
        $category->delete();
        return response()->json(['message' => 'Kategori berhasil dihapus']);
    }

    public function menus()
    {
        return response()->json(
            Menu::with('category', 'modifiers')->get()
        );
    }

    public function storeMenu(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|string|max:255',
            'is_available' => 'boolean',
        ]);

        $menu = Menu::create($request->all());

        return response()->json($menu->load('category', 'modifiers'), 201);
    }

    public function updateMenu(Request $request, Menu $menu)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|string|max:255',
            'is_available' => 'boolean',
        ]);

        $menu->update($request->all());

        return response()->json($menu->load('category', 'modifiers'));
    }

    public function destroyMenu(Menu $menu)
    {
        $menu->delete();
        return response()->json(['message' => 'Menu berhasil dihapus']);
    }

    public function modifierByMenu(Menu $menu)
    {
        return response()->json($menu->modifiers);
    }

    public function storeModifier(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'name' => 'required|string|max:100',
            'extra_price' => 'required|numeric|min:0',
        ]);

        $modifier = Modifier::create($request->all());

        return response()->json($modifier, 201);
    }

    public function updateModifier(Request $request, Modifier $modifier)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'extra_price' => 'required|numeric|min:0',
        ]);

        $modifier->update($request->all());

        return response()->json($modifier);
    }

    public function destroyModifier(Modifier $modifier)
    {
        $modifier->delete();
        return response()->json(['message' => 'Modifier berhasil dihapus']);
    }

    public function orders()
    {
        return response()->json(
            Order::with('table', 'items.menu')
                ->orderByDesc('created_at')
                ->paginate(20)
        );
    }

    public function cancelOrder(Order $order)
    {
        if (in_array($order->order_status, ['completed', 'cancelled'])) {
            return response()->json([
                'message' => 'Pesanan sudah selesai atau dibatalkan',
            ], 422);
        }

        $order->update(['order_status' => 'cancelled']);

        $hasActiveOrders = $order->table->orders()
            ->where('order_status', '!=', 'completed')
            ->where('order_status', '!=', 'cancelled')
            ->exists();

        if (!$hasActiveOrders) {
            $order->table->update(['status' => 'empty']);
        }

        return response()->json([
            'message' => 'Pesanan dibatalkan',
            'order' => $order,
        ]);
    }
}
