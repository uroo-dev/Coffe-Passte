<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        $tables = [];
        for ($i = 1; $i <= 20; $i++) {
            $tables[] = [
                'table_number' => str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                'qr_token' => Str::random(40),
                'status' => 'empty',
            ];
        }

        foreach ($tables as $table) {
            Table::create($table);
        }
    }
}
