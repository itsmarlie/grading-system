// database/migrations/xxxx_create_sections_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Sections belong to a course
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g. "A", "B", "Section 1"
            $table->integer('max_students')->default(40);
            $table->timestamps();
        });

        // Each section can have multiple schedule slots
        Schema::create('section_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained()->onDelete('cascade');
            $table->string('day'); // Monday, Tuesday, etc.
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room')->nullable();
            $table->timestamps();
        });

        // Regular students → assigned to one section
        // Irregular students → assigned to courses directly (existing enrollments table)
        if (!Schema::hasTable('student_sections')) {
            Schema::create('student_sections', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained()->onDelete('cascade');
                $table->foreignId('section_id')->constrained()->onDelete('cascade');
                $table->string('student_type')->default('regular'); // regular | irregular
                $table->timestamps();
                $table->unique(['student_id','section_id']);
            });
        }
    }
    public function down(): void {
        Schema::dropIfExists('student_sections');
        Schema::dropIfExists('section_schedules');
        Schema::dropIfExists('sections');
    }
};