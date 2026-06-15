<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('attendances', 'course_id')) {
                $table->foreignId('course_id')->after('student_id')
                      ->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('attendances', 'remarks')) {
                $table->string('remarks')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropColumn(['course_id', 'remarks']);
        });
    }
};