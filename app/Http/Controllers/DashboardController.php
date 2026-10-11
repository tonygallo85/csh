<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->is_admin) {
            $courses = Course::all();
        } elseif ($user->is_teacher) {
            $courses = $user->taughtCourses;
        } else {
            $courses = $user->enrolledCourses;
        }

        return view('userzone.dashboard', ['courses' => $courses]);
    }
}
