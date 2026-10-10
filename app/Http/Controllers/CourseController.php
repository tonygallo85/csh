<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::all();

        return view('courses.index', ['courses' => $courses]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Course::class);

        $teachers = User::where('is_teacher', true)->get();

        return view('courses.create', ['teachers' => $teachers]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Course::class);

        $request->validate([
            'language' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:255'],
            'schedule' => ['required', 'string', 'max:255'],
        ]);

        $course = new Course;

        $course->language = $request->input('language');
        $course->level = $request->input('level');
        $course->schedule = $request->input('schedule');

        if ($request->user()->is_admin) {
            $course->teacher_id = $request->input('teacher_id');
        } else {
            $course->teacher_id = $request->user()->id;
        }

        if ($request->user()->is_admin) {
            $request->validate([
                'teacher_id' => ['required', Rule::exists('users', 'id')->where('is_teacher', true)],
            ]);

            $course->teacher_id = $request->input('teacher_id');
        } else {
            $course->teacher_id = $request->user()->id;
        }

        $course->save();

        return redirect()->route('courses.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        return view('courses.show', ['course' => $course]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        //
    }
}
