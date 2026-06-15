<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\GradebookController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\SyllabusController;

use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\SettingsController;

use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\StudentGradeController;
use App\Http\Controllers\Student\StudentAttendanceController;
use App\Http\Controllers\Student\StudentSyllabusController;

// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
});


// =========================
// AUTHENTICATED USERS
// =========================
Route::middleware('auth')->group(function () {

    // Dashboard (redirects students to their own dashboard)
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');


    // =========================
    // PROFILE
    // =========================
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    // =========================
    // SEARCH
    // =========================
    Route::get('/search', SearchController::class)
        ->name('search');


    // =========================
    // NOTIFICATIONS
    // =========================
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');


    // =========================
    // ANNOUNCEMENTS (all authenticated users)
    // =========================
    Route::get('/announcements', [AnnouncementController::class, 'index'])
        ->name('announcements.index');

    Route::get('/announcements/create', [AnnouncementController::class, 'create'])
        ->name('announcements.create');

    Route::get('/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])
        ->name('announcements.edit');

    Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show'])
        ->name('announcements.show');


    // =========================
    // API ROUTES
    // =========================
    Route::prefix('api')->group(function () {
        Route::get('/search', [ApiController::class, 'search']);
        Route::get('/notifications', [ApiController::class, 'notifications']);
    });


    // =========================
    // STUDENT PAGES
    // =========================
    Route::middleware('role:student')->group(function () {

        Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])
            ->name('student.dashboard');

        Route::get('/my/grades', [StudentGradeController::class, 'index'])
            ->name('my.grades');

        Route::get('/my/attendance', [StudentAttendanceController::class, 'index'])
            ->name('my.attendance');

        Route::get('/my/syllabus', [StudentSyllabusController::class, 'index'])
            ->name('my.syllabus');

        Route::get('/my/syllabus/{id}', [StudentSyllabusController::class, 'show'])
            ->name('my.syllabus.show');
    });


    // =========================
    // ADMIN + TEACHER
    // =========================
    Route::middleware('role:admin,teacher')->group(function () {

        // Create users
        Route::get('/create-user', [AdminUserController::class, 'create'])
            ->name('create-user');
        Route::post('/create-user', [AdminUserController::class, 'store'])
            ->name('store-user');


        // Announcements write actions
        Route::post('/announcements', [AnnouncementController::class, 'store'])
            ->name('announcements.store');

        Route::patch('/announcements/{announcement}', [AnnouncementController::class, 'update'])
            ->name('announcements.update');

        Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])
            ->name('announcements.destroy');

        // Students, Courses, Assignments
        Route::resource('students', StudentController::class);
        Route::resource('courses', CourseController::class);

        // Assign students to a course
        Route::get('/courses/{course}/assign-students', [StudentController::class, 'assignCourse'])
            ->name('students.course.assign');
        Route::put('/courses/{course}/assign-students', [StudentController::class, 'saveAssignCourse'])
            ->name('students.course.save');

        // Gradebook
        Route::get('/gradebook', [GradebookController::class, 'index'])
            ->name('gradebook.index');

        Route::get('/gradebook/{course}', [GradebookController::class, 'show'])
            ->name('gradebook.show');

        Route::post('/gradebook/{course}', [GradebookController::class, 'update'])
            ->name('gradebook.update');

        // Attendance
        Route::get('/attendance', [AttendanceController::class, 'index'])
            ->name('attendance.index');

        Route::get('/attendance/{course}', [AttendanceController::class, 'show'])
            ->name('attendance.show');

        Route::post('/attendance', [AttendanceController::class, 'store'])
            ->name('attendance.store');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export/csv', [ReportController::class, 'exportCsv'])->name('reports.export.csv');
        Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');

        // Syllabus management (admin + teacher create/edit/delete)
        Route::resource('syllabi', SyllabusController::class);
    });


    // =========================
    // ADMIN ONLY
    // =========================
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

        Route::resource('sections', SectionController::class);

        // Assign students to section
        Route::get('/sections/{section}/assign-students', [SectionController::class, 'assignStudents'])
            ->name('sections.students.assign');
        Route::put('/sections/{section}/assign-students', [SectionController::class, 'saveAssignStudents'])
            ->name('sections.students.save');

        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    });

            // =========================
            // TEACHER ONLY
            // =========================
            Route::middleware(['auth', 'role:teacher'])->group(function () {
            Route::resource('assignments', AssignmentController::class);

    });

});


// Auth routes
require __DIR__.'/auth.php';