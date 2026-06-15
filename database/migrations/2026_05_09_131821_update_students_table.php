<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'first_name')) {
                $table->string('first_name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('students', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('students', 'gender')) {
                $table->string('gender')->nullable()->after('last_name');
            }
            if (!Schema::hasColumn('students', 'phone')) {
                $table->string('phone')->nullable()->after('address');
            }
            if (!Schema::hasColumn('students', 'course_id')) {
                $table->foreignId('course_id')->nullable()->after('course')
                      ->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('students', 'term')) {
                $table->string('term')->nullable()->after('year_level');
            }
            if (!Schema::hasColumn('students', 'status')) {
                $table->enum('status', ['active', 'inactive', 'graduated'])
                      ->default('active')->after('term');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Must drop foreign key before dropping the column
            if (Schema::hasColumn('students', 'course_id')) {
                $table->dropForeign(['course_id']);
                $table->dropColumn('course_id');
            }
            $columns = ['first_name', 'last_name', 'gender', 'phone', 'term', 'status'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('students', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};