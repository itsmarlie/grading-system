<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('settings')->insertOrIgnore([
            'key'        => 'current_semester',
            'value'      => 'S.Y. 2025-2026 Sem 2',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}