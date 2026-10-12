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
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->is_teacher || $user->is_admin) {
            $courses = Course::all();
        } else {
            $courses = Course::whereDoesntHave('enrollments', function ($query) use ($user) {
                $query->where('student_id', $user->id);
            })->get();
        }

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
            'level' => ['required', Rule::in(['A1', 'A2', 'B1', 'B2', 'C1', 'C2'])],
            'schedule' => ['required', 'string', 'max:255'],
        ]);

        $course = new Course;

        $course->language = $request->input('language');
        $course->level = $request->input('level');
        $course->schedule = $request->input('schedule');

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
    public function show(Request $request, Course $course)
    {
        $enrollment = $course->enrollments()->where('student_id', $request->user()->id)->first();

        return view('courses.show', [
            'course' => $course,
            'enrollment' => $enrollment,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        Gate::authorize('update', $course);

        $teachers = User::where('is_teacher', true)->get();

        return view('courses.edit', ['course' => $course, 'teachers' => $teachers]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        Gate::authorize('update', $course);

        $request->validate([
            'language' => ['required', 'string', 'max:255'],
            'level' => ['required', Rule::in(['A1', 'A2', 'B1', 'B2', 'C1', 'C2'])],
            'schedule' => ['required', 'string', 'max:255'],
        ]);

        $course->language = $request->input('language');
        $course->level = $request->input('level');
        $course->schedule = $request->input('schedule');

        if ($request->user()->is_admin) {
            $request->validate([
                'teacher_id' => ['required', Rule::exists('users', 'id')->where('is_teacher', true)],
            ]);

            $course->teacher_id = $request->input('teacher_id');
        }

        $course->save();

        return redirect()->route('courses.show', $course);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        Gate::authorize('delete', $course);

        $course->delete();

        return redirect()->route('courses.index');
    }
}
