<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $activeSemester   = Setting::get('active_semester', '1st Semester');
        $activeSchoolYear = Setting::get('active_school_year', '2025-2026');
        return view('admin.settings.index', compact('activeSemester', 'activeSchoolYear'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'active_semester'    => 'required|in:1st Semester,2nd Semester,Summer',
            'active_school_year' => 'required|regex:/^\d{4}-\d{4}$/',
        ]);

        Setting::set('active_semester',    $request->active_semester);
        Setting::set('active_school_year', $request->active_school_year);

        return back()->with('success', 'Semester settings updated.');
    }
}