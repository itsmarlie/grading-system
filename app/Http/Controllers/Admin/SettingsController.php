<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
        public function index()
        {
            $activeSemester   = Setting::get('active_semester', '1st Semester');
            $activeSchoolYear = Setting::get('active_school_year', '2025-2026');

            return view('admin.settings', compact('activeSemester', 'activeSchoolYear'));
        }

        public function update(Request $request)
        {
            $request->validate([
                'active_semester'   => 'required|string|max:100',
                'active_school_year' => 'required|string|max:20',
            ]);

            Setting::set('active_semester',    $request->active_semester);
            Setting::set('active_school_year', $request->active_school_year);

            return back()->with('success', 'Settings updated successfully!');
}
}