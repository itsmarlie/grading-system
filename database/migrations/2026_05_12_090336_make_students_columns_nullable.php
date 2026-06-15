<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('students', function (Blueprint $table) {
            $table->string('course')->nullable()->change();
            $table->string('year_level')->nullable()->change();
            $table->string('term')->nullable()->change();
            $table->string('section')->nullable()->change();
            $table->string('status')->default('active')->change();
        });
    }
    public function down(): void {
        Schema::table('students', function (Blueprint $table) {
            $table->string('course')->nullable(false)->change();
            $table->string('year_level')->nullable(false)->change();
            $table->string('term')->nullable(false)->change();
        });
    }
};