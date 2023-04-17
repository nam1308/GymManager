<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CourseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function index()
    {
        $user = Auth::user();
        $courses = Course::where('vendor_id', $user->vendor_id)->get();
        return view('course.index')->with([
            'courses' => $courses
        ]);
    }


    //
    public function show(int $course_id): View
    {
        $course = Course::find($course_id);
        return view('course.show')->with(['course' => $course]);
    }
}
