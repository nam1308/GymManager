<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Mockery\Exception;
use Throwable;

class CourseController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $admin = Auth::guard('admin')->user();
        $courses = Course::where('vendor_id', $admin->vendor_id)->orderBy('id', 'DESC')->limit(30)->get();
        return view('admin.course.index')->with(['courses' => $courses]);
    }

    /**
     * @return \Illuminate\View\View
     * @throws \Exception
     */
    public function create(): View
    {
        $times = get_course_times();
        return view('admin.course.create')->with(['times' => $times]);
    }

    /**
     * @param \App\Http\Requests\CreateCourseRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateCourseRequest $request): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();
        $course = new Course();
        $course->vendor_id = $admin->vendor_id;
        $course->name = $request->name;
        $course->price = $request->price;
        $course->time = $request->course_time + $request->course_minutes;
        $course->course_time = $request->course_time;
        $course->course_minutes = $request->course_minutes;
        $course->contents = $request->contents;
        $course->save();
        return redirect(route('admin.course'))
            ->with('flash_message_success', '登録しました');
    }

    /**
     * @param int $course_id
     * @return \Illuminate\View\View
     * @throws \Exception
     */
    public function edit(int $course_id): View
    {
        $times = get_course_times();
        $admin = Auth::guard('admin')->user();
        $course = Course::where(['vendor_id' => $admin->vendor_id, 'id' => $course_id])->first();
        return view('admin.course.edit')->with([
            'course' => $course,
            'times' => $times,
            'course_id' => $course_id,
        ]);
    }

    /**
     * @param \App\Http\Requests\UpdateCourseRequest $request
     * @param $course_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCourseRequest $request, $course_id): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();
        $course = Course::where(['vendor_id' => $admin->vendor_id, 'id' => $course_id])->first();
        try {
            if (!$course) {
                throw new Exception('メニューが見つかりません');
            }
            DB::transaction(function () use ($request, $course_id) {
                $course = Course::find($course_id);
                $course->name = $request->name;
                $course->price = $request->price;
                $course->time = $request->course_time + $request->course_minutes;
                $course->course_time = $request->course_time;
                $course->course_minutes = $request->course_minutes;
                $course->contents = $request->contents;
                $course->save();
                return $course;
            });
            return redirect(route('admin.course'))
                ->with('flash_message_success', '保存しました');
        } catch (Throwable $e) {
            return redirect(route('admin.course.edit', $course_id))
                ->with('flash_message_danger', '保存失敗');
        }
    }

    /**
     * @param int $course_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $course_id): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();
        $course = Course::where(['vendor_id' => $admin->vendor_id, 'id' => $course_id])->first();
        if ($course) {
            $course->delete();
            return redirect(route('admin.course'))
                ->with('flash_message_success', '削除しました');
        } else {
            return redirect(route('admin.course'))
                ->with('flash_message_danger', '削除失敗');
        }
    }
}
