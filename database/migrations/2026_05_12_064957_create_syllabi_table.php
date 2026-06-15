<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('syllabi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users');
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('course_overview')->nullable();
            $table->text('learning_outcomes')->nullable();
            $table->json('grading_criteria')->nullable();  // [{"type":"Quiz","weight":20,"description":"..."}]
            $table->json('materials')->nullable();          // [{"title":"...","url":"...","type":"pdf|link|file|video"}]
            $table->json('topics')->nullable();             // [{"week":1,"title":"...","description":"...","activities":[]}]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syllabi');
    }
};
