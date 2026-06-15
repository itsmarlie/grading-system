<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Student, Course, User};
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $q = trim($request->input('q', ''));
        if (strlen($q) < 2) {
            return response()->json(['results' => []]);
        }

        $results = [];

        // Students
        $students = Student::where(function($query) use ($q) {
            $query->where('first_name', 'like', "%{$q}%")
                  ->orWhere('last_name', 'like', "%{$q}%")
                  ->orWhere('middle_name', 'like', "%{$q}%")
                  ->orWhere('student_number', 'like', "%{$q}%");
        })->limit(5)->get();

        foreach ($students as $s) {
            $results[] = [
                'type'  => 'student',
                'label' => $s->display_name . ' (' . $s->student_number . ')',
                'url'   => route('admin.students.show', $s->id),
                'icon'  => 'user-graduate',
            ];
        }

        // Courses
        $courses = Course::where('name', 'like', "%{$q}%")
            ->orWhere('code', 'like', "%{$q}%")
            ->limit(5)->get();

        foreach ($courses as $c) {
            $results[] = [
                'type'  => 'course',
                'label' => $c->code . ' — ' . $c->name,
                'url'   => route('admin.courses.show', $c->id),
                'icon'  => 'book',
            ];
        }

        // Assignments
        if (class_exists(\App\Models\Assignment::class)) {
            $assignments = \App\Models\Assignment::where('title', 'like', "%{$q}%")
                ->orWhere('code', 'like', "%{$q}%")
                ->limit(5)->get();
            foreach ($assignments as $a) {
                $results[] = [
                    'type'  => 'assignment',
                    'label' => ($a->code ? "[{$a->code}] " : '') . $a->title,
                    'url'   => '#',
                    'icon'  => 'file-alt',
                ];
            }
        }

        // Announcements
        if (class_exists(\App\Models\Announcement::class)) {
            $announcements = \App\Models\Announcement::where('title', 'like', "%{$q}%")
                ->orWhere('content', 'like', "%{$q}%")
                ->limit(5)->get();
            foreach ($announcements as $a) {
                $results[] = [
                    'type'  => 'announcement',
                    'label' => $a->title,
                    'url'   => '#',
                    'icon'  => 'bullhorn',
                ];
            }
        }

        // Reports
        $results[] = [
            'type'  => 'report',
            'label' => 'Attendance Report',
            'url'   => route('admin.reports.attendance'),
            'icon'  => 'chart-bar',
        ];

        // Filter to only matching report links if query matches
        $results = array_filter($results, fn($r) => true); // already filtered above

        return response()->json(['results' => array_values($results)]);
    }
}