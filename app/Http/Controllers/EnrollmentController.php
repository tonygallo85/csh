<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EnrollmentController extends Controller
{
    public function store(Request $request, Course $course)
    {
        Gate::authorize('enroll', $course);

        $enrollment = new Enrollment;

        $enrollment->student_id = $request->user()->id;
        $enrollment->course_id = $course->id;

        $enrollment->save();

        return redirect()->route('courses.show', ['course' => $course]);
    }
}
