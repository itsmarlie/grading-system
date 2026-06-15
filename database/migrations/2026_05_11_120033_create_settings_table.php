<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Table already created by 2026_05_10_010351_create_settings_table
        // Just seed the default values
        DB::table('settings')->insertOrIgnore([
            ['key' => 'active_semester',    'value' => '1st Semester'],
            ['key' => 'active_school_year', 'value' => '2025-2026'],
        ]);
    }
    public function down(): void {
        DB::table('settings')->whereIn('key', ['active_semester', 'active_school_year'])->delete();
    }
};