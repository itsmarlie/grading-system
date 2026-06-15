<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseSchedule extends Model
{
    protected $fillable = ['course_id', 'day', 'time_start', 'time_end', 'room'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}