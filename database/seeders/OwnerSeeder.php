<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Owner Coffee Paste',
            'email' => 'owner@coffepaste.com',
            'password' => Hash::make('owner123'),
            'role' => 'owner',
        ]);
    }
}
