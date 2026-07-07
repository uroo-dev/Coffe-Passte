<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $tables = Table::all();
        $menus = Menu::all();

        // Order 1: Table 01 - completed & paid
        $order1 = Order::create([
            'table_id' => $tables[0]->id,
            'order_reference' => 'SR-' . now()->format('Ymd') . '-001',
            'total_amount' => 60000,
            'order_status' => 'completed',
            'payment_status' => 'paid',
            'payment_method' => 'qris',
        ]);
        $order1->items()->createMany([
            ['menu_id' => $menus->firstWhere('name', 'Nasi Goreng')->id, 'quantity' => 2, 'notes' => 'Level 2', 'price_at_order' => 25000],
            ['menu_id' => $menus->firstWhere('name', 'Es Campur')->id, 'quantity' => 1, 'notes' => null, 'price_at_order' => 15000],
        ]);

        // Order 2: Table 03 - cooking & unpaid
        $order2 = Order::create([
            'table_id' => $tables[2]->id,
            'order_reference' => 'SR-' . now()->format('Ymd') . '-002',
            'total_amount' => 52000,
            'order_status' => 'cooking',
            'payment_status' => 'unpaid',
            'payment_method' => null,
        ]);
        $order2->items()->createMany([
            ['menu_id' => $menus->firstWhere('name', 'Ayam Penyet')->id, 'quantity' => 1, 'notes' => 'Level 3, Nasi Tambahan', 'price_at_order' => 30000],
            ['menu_id' => $menus->firstWhere('name', 'Teh Manis')->id, 'quantity' => 2, 'notes' => null, 'price_at_order' => 5000],
            ['menu_id' => $menus->firstWhere('name', 'Pisang Goreng')->id, 'quantity' => 1, 'notes' => 'Topping Coklat', 'price_at_order' => 10000],
        ]);

        // Order 3: Table 05 - pending & unpaid
        $order3 = Order::create([
            'table_id' => $tables[4]->id,
            'order_reference' => 'SR-' . now()->format('Ymd') . '-003',
            'total_amount' => 67000,
            'order_status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => null,
        ]);
        $order3->items()->createMany([
            ['menu_id' => $menus->firstWhere('name', 'Sate Ayam')->id, 'quantity' => 1, 'notes' => null, 'price_at_order' => 28000],
            ['menu_id' => $menus->firstWhere('name', 'Cappuccino')->id, 'quantity' => 1, 'notes' => 'Extra Shot', 'price_at_order' => 25000],
            ['menu_id' => $menus->firstWhere('name', 'Kentang Goreng')->id, 'quantity' => 1, 'notes' => 'Extra Keju', 'price_at_order' => 15000],
        ]);

        // Order 4: Table 07 - ready & unpaid
        $order4 = Order::create([
            'table_id' => $tables[6]->id,
            'order_reference' => 'SR-' . now()->format('Ymd') . '-004',
            'total_amount' => 27000,
            'order_status' => 'ready',
            'payment_status' => 'unpaid',
            'payment_method' => null,
        ]);
        $order4->items()->createMany([
            ['menu_id' => $menus->firstWhere('name', 'Cold Brew')->id, 'quantity' => 1, 'notes' => null, 'price_at_order' => 30000],
        ]);

        // Order 5: Table 10 - completed & paid (cash)
        $order5 = Order::create([
            'table_id' => $tables[9]->id,
            'order_reference' => 'SR-' . now()->format('Ymd') . '-005',
            'total_amount' => 22000,
            'order_status' => 'completed',
            'payment_status' => 'paid',
            'payment_method' => 'cash',
        ]);
        $order5->items()->createMany([
            ['menu_id' => $menus->firstWhere('name', 'Mie Goreng')->id, 'quantity' => 1, 'notes' => null, 'price_at_order' => 22000],
        ]);
    }
}
