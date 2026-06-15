<?php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use App\Models\Announcement;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function search(Request $request)
    {
        $q = '%' . $request->input('q', '') . '%';
        $user = auth()->user();

        // Students
        $studentsQuery = Student::with('course')
            ->where(function($query) use ($q) {
                $query->where('first_name', 'like', $q)
                      ->orWhere('last_name', 'like', $q)
                      ->orWhere('student_number', 'like', $q);
            });

        if ($user->role === 'teacher') {
            $studentsQuery->whereHas('course', fn($c) => $c->where('teacher_id', $user->id));
        }

        $students = $studentsQuery->limit(5)->get()->map(fn($s) => [
            'name' => $s->first_name . ' ' . $s->last_name,
            'sub'  => $s->student_number . ' · ' . ($s->course->course_name ?? '—'),
            'url'  => route('students.show', $s->id),
        ]);

        // Courses
        $coursesQuery = Course::where('course_name', 'like', $q)
                              ->orWhere('course_code', 'like', $q);
        if ($user->role === 'teacher') {
            $coursesQuery->where('teacher_id', $user->id);
        }
        $courses = $coursesQuery->limit(5)->get()->map(fn($c) => [
            'name' => $c->course_name,
            'sub'  => $c->course_code ?? '',
            'url'  => route('courses.show', $c->id),
        ]);

        // Announcements
        $announcements = Announcement::where('title', 'like', $q)
            ->latest()->limit(3)->get()->map(fn($a) => [
                'name' => $a->title,
                'sub'  => $a->created_at->format('M d, Y'),
                'url'  => route('announcements.show', $a->id),
            ]);

        return response()->json([
            'students'      => $students,
            'courses'       => $courses,
            'announcements' => $announcements,
        ]);
    }

    public function notifications()
    {
        $announcements = Announcement::latest()->limit(8)->get()->map(fn($a) => [
            'title' => $a->title,
            'date'  => $a->created_at->format('M d, Y'),
            'url'   => route('announcements.show', $a->id),
        ]);

        return response()->json($announcements);
    }
}