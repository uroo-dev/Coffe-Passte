<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Modifier;
use Illuminate\Database\Seeder;

class ModifierSeeder extends Seeder
{
    public function run(): void
    {
        $menus = Menu::all();

        $modifiers = [
            'Nasi Goreng' => [
                ['name' => 'Level 1', 'extra_price' => 0],
                ['name' => 'Level 2', 'extra_price' => 0],
                ['name' => 'Level 3', 'extra_price' => 0],
                ['name' => 'Level 5', 'extra_price' => 0],
                ['name' => 'Ekstra Telur', 'extra_price' => 5000],
                ['name' => 'Ekstra Ayam', 'extra_price' => 10000],
            ],
            'Mie Goreng' => [
                ['name' => 'Level 1', 'extra_price' => 0],
                ['name' => 'Level 2', 'extra_price' => 0],
                ['name' => 'Level 3', 'extra_price' => 0],
                ['name' => 'Ekstra Bakso', 'extra_price' => 5000],
                ['name' => 'Ekstra Telur', 'extra_price' => 5000],
            ],
            'Ayam Penyet' => [
                ['name' => 'Level 1', 'extra_price' => 0],
                ['name' => 'Level 2', 'extra_price' => 0],
                ['name' => 'Level 3', 'extra_price' => 0],
                ['name' => 'Nasi Tambahan', 'extra_price' => 4000],
            ],
            'Sate Ayam' => [
                ['name' => 'Lontong', 'extra_price' => 3000],
                ['name' => 'Nasi', 'extra_price' => 4000],
                ['name' => 'Sambal Matang', 'extra_price' => 0],
            ],
            'Espresso' => [
                ['name' => 'Extra Shot', 'extra_price' => 5000],
                ['name' => 'Susu', 'extra_price' => 4000],
            ],
            'Cappuccino' => [
                ['name' => 'Extra Shot', 'extra_price' => 5000],
                ['name' => 'Extra Foam', 'extra_price' => 3000],
                ['name' => 'Soy Milk', 'extra_price' => 5000],
            ],
            'Latte' => [
                ['name' => 'Extra Shot', 'extra_price' => 5000],
                ['name' => 'Caramel Syrup', 'extra_price' => 4000],
                ['name' => 'Vanilla Syrup', 'extra_price' => 4000],
                ['name' => 'Soy Milk', 'extra_price' => 5000],
            ],
            'Kentang Goreng' => [
                ['name' => 'Extra Saus', 'extra_price' => 2000],
                ['name' => 'Extra Keju', 'extra_price' => 5000],
                ['name' => 'Extra Saus Sambal', 'extra_price' => 2000],
            ],
            'Pisang Goreng' => [
                ['name' => 'Topping Coklat', 'extra_price' => 3000],
                ['name' => 'Topping Keju', 'extra_price' => 3000],
                ['name' => 'Ice Cream', 'extra_price' => 5000],
            ],
            'Pancake' => [
                ['name' => 'Extra Madu', 'extra_price' => 5000],
                ['name' => 'Ice Cream', 'extra_price' => 7000],
                ['name' => 'Toping Stroberi', 'extra_price' => 5000],
            ],
        ];

        foreach ($modifiers as $menuName => $items) {
            $menu = $menus->firstWhere('name', $menuName);
            if (!$menu) continue;

            foreach ($items as $item) {
                Modifier::create([
                    'menu_id' => $menu->id,
                    'name' => $item['name'],
                    'extra_price' => $item['extra_price'],
                ]);
            }
        }
    }
}
