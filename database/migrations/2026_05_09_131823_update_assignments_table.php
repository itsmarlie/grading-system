<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            if (!Schema::hasColumn('assignments', 'type')) {
                $table->enum('type', ['written_work', 'performance_task', 'quarterly_assessment'])
                      ->default('written_work')->after('title');
            }
            if (!Schema::hasColumn('assignments', 'term')) {
                $table->string('term')->nullable()->after('type');
            }
            if (!Schema::hasColumn('assignments', 'status')) {
                $table->enum('status', ['open', 'closed'])->default('open')->after('due_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn(['type', 'term', 'status']);
        });
    }
};