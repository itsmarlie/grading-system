<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'units')) {
                $table->integer('units')->default(3)->after('description');
            }
            if (!Schema::hasColumn('courses', 'schedule')) {
                $table->string('schedule')->nullable()->after('units');
            }
            if (!Schema::hasColumn('courses', 'room')) {
                $table->string('room')->nullable()->after('schedule');
            }
            if (!Schema::hasColumn('courses', 'term')) {
                $table->string('term')->nullable()->after('room');
            }
            if (!Schema::hasColumn('courses', 'school_year')) {
                $table->string('school_year')->nullable()->after('term');
            }
            if (!Schema::hasColumn('courses', 'ww_weight')) {
                $table->integer('ww_weight')->default(25)->after('school_year');
            }
            if (!Schema::hasColumn('courses', 'pt_weight')) {
                $table->integer('pt_weight')->default(50)->after('ww_weight');
            }
            if (!Schema::hasColumn('courses', 'qa_weight')) {
                $table->integer('qa_weight')->default(25)->after('pt_weight');
            }
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'units', 'schedule', 'room', 'term',
                'school_year', 'ww_weight', 'pt_weight', 'qa_weight'
            ]);
        });
    }
};