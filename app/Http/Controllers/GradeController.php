<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grade;

class GradeController extends Controller
{
    public function store(Request $request)
    {
        Grade::create($request->all());

        return back();
    }

    public function show($studentId)
    {
        $grades = Grade::where('student_id', $studentId)->get();

        $average = $grades->avg('score');

        return view('grades.show', compact('grades', 'average'));
    }
}