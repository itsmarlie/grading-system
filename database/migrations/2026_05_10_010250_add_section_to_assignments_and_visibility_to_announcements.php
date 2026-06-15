<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('assignments', function (Blueprint $table) {
            if (!Schema::hasColumn('assignments', 'section')) {
                $table->string('section')->nullable()->after('term');
            }
        });
        Schema::table('announcements', function (Blueprint $table) {
            if (!Schema::hasColumn('announcements', 'visibility')) {
                $table->enum('visibility', ['all', 'teachers', 'students'])->default('all')->after('category');
            }
        });
    }
    public function down(): void {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn('section');
        });
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn('visibility');
        });
    }
};