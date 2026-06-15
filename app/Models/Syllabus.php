<?php
// ══════════════════════════════════════════════════════
// app/Models/Syllabus.php
// ══════════════════════════════════════════════════════
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Syllabus extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'course_overview',
        'learning_outcomes',
        'grading_criteria',   // JSON: [{"type":"Quiz","weight":20}, ...]
        'materials',          // JSON: [{"title":"...", "url":"...", "type":"pdf|link|file"}]
        'topics',             // JSON: [{"week":1, "title":"...", "description":"..."}]
        'created_by',
    ];

    protected $casts = [
        'grading_criteria'  => 'array',
        'materials'         => 'array',
        'topics'            => 'array',
    ];

    public function course()  { return $this->belongsTo(Course::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}