<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['course_id', 'name', 'max_students'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function schedules()
    {
        return $this->hasMany(SectionSchedule::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_sections')
                    ->withPivot('student_type')
                    ->withTimestamps();
    }

    public function getScheduleSummaryAttribute(): string
    {
        return $this->schedules->map(fn($s) =>
            "{$s->day} " .
            \Carbon\Carbon::parse($s->start_time)->format('h:i A') . '–' .
            \Carbon\Carbon::parse($s->end_time)->format('h:i A') .
            ($s->room ? " ({$s->room})" : '')
        )->implode(', ');
    }
}