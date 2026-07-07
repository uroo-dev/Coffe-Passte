<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id', 'slug');

        $menus = [
            // Makanan
            ['category_id' => $categories['makanan'], 'name' => 'Nasi Goreng', 'description' => 'Nasi goreng spesial dengan telur dan ayam', 'price' => 25000, 'is_available' => true],
            ['category_id' => $categories['makanan'], 'name' => 'Mie Goreng', 'description' => 'Mie goreng dengan sayuran dan bakso', 'price' => 22000, 'is_available' => true],
            ['category_id' => $categories['makanan'], 'name' => 'Ayam Penyet', 'description' => 'Ayam penyet sambal terasi', 'price' => 30000, 'is_available' => true],
            ['category_id' => $categories['makanan'], 'name' => 'Sate Ayam', 'description' => 'Sate ayam dengan bumbu kacang', 'price' => 28000, 'is_available' => true],
            // Minuman
            ['category_id' => $categories['minuman'], 'name' => 'Teh Manis', 'description' => 'Teh manis segar', 'price' => 5000, 'is_available' => true],
            ['category_id' => $categories['minuman'], 'name' => 'Jus Jeruk', 'description' => 'Jus jeruk peras segar', 'price' => 12000, 'is_available' => true],
            ['category_id' => $categories['minuman'], 'name' => 'Es Campur', 'description' => 'Es campur dengan buah segar', 'price' => 15000, 'is_available' => true],
            // Snack
            ['category_id' => $categories['snack'], 'name' => 'Kentang Goreng', 'description' => 'Kentang goreng crispy', 'price' => 15000, 'is_available' => true],
            ['category_id' => $categories['snack'], 'name' => 'Pisang Goreng', 'description' => 'Pisang goreng dengan topping coklat', 'price' => 10000, 'is_available' => true],
            ['category_id' => $categories['snack'], 'name' => 'Cireng', 'description' => 'Cireng bumbu rujak', 'price' => 12000, 'is_available' => true],
            // Dessert
            ['category_id' => $categories['dessert'], 'name' => 'Pancake', 'description' => 'Pancake dengan madu dan stroberi', 'price' => 25000, 'is_available' => true],
            ['category_id' => $categories['dessert'], 'name' => 'Puding', 'description' => 'Puding coklat dengan vla', 'price' => 15000, 'is_available' => true],
            // Coffee
            ['category_id' => $categories['coffee'], 'name' => 'Espresso', 'description' => 'Espresso single shot', 'price' => 18000, 'is_available' => true],
            ['category_id' => $categories['coffee'], 'name' => 'Cappuccino', 'description' => 'Cappuccino dengan foam susu', 'price' => 25000, 'is_available' => true],
            ['category_id' => $categories['coffee'], 'name' => 'Latte', 'description' => 'Caffe latte dengan susu segar', 'price' => 27000, 'is_available' => true],
            ['category_id' => $categories['coffee'], 'name' => 'Cold Brew', 'description' => 'Cold brew smooth dan strong', 'price' => 30000, 'is_available' => true],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}
