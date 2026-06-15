<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'first_name', 'middle_name', 'last_name',
        'student_number', 'course_id', 'year_level',
        'section', 'term', 'status', 'student_type',
        'gender', 'birthdate', 'address', 'phone',
    ];

    protected $casts = ['birthdate' => 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function sections()
    {
        return $this->belongsToMany(Section::class, 'student_sections')
                    ->withPivot('student_type')
                    ->withTimestamps();
    }

    public function getFullNameAttribute(): string
    {
        $parts = array_filter([$this->first_name, $this->middle_name, $this->last_name]);
        return implode(' ', $parts) ?: '';
    }

    public function getDisplayNameAttribute(): string
    {
        $mn    = $this->middle_name ? substr($this->middle_name, 0, 1) . '.' : '';
        $parts = array_filter([$this->first_name, $mn, $this->last_name]);
        return implode(' ', $parts);
    }
}