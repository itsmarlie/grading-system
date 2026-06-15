<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('course_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('day'); // Monday, Tuesday, etc.
            $table->string('time_start'); // e.g. 07:30
            $table->string('time_end');   // e.g. 08:30
            $table->string('room')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('course_schedules');
    }
};