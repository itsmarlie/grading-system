<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('grades')) {
            Schema::create('grades', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained()->onDelete('cascade');
                $table->foreignId('assignment_id')->constrained()->onDelete('cascade');
                $table->foreignId('course_id')->constrained()->onDelete('cascade');
                $table->decimal('score', 5, 2)->nullable();
                $table->string('term')->nullable();
                $table->text('remarks')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('grades', function (Blueprint $table) {
                if (!Schema::hasColumn('grades', 'student_id'))
                    $table->foreignId('student_id')->constrained()->onDelete('cascade');
                if (!Schema::hasColumn('grades', 'assignment_id'))
                    $table->foreignId('assignment_id')->constrained()->onDelete('cascade');
                if (!Schema::hasColumn('grades', 'course_id'))
                    $table->foreignId('course_id')->constrained()->onDelete('cascade');
                if (!Schema::hasColumn('grades', 'score'))
                    $table->decimal('score', 5, 2)->nullable();
                if (!Schema::hasColumn('grades', 'term'))
                    $table->string('term')->nullable();
                if (!Schema::hasColumn('grades', 'remarks'))
                    $table->text('remarks')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};