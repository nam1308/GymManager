<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminProfilePhoto;
use App\Models\BusinessHours;
use App\Models\Course;
use App\Models\LineMessage;
use App\Models\Reservation;
use App\Models\Shop;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function index()
    {
        $start = config('const.DEFAULT_BUSINESS_HOURS.START');
        $end = config('const.DEFAULT_BUSINESS_HOURS.END');
        $admin = Auth::user();
        if (!$admin->self_introduction) {
            return redirect(route('admin.profile.edit'));
        }
        $admin_profile = AdminProfilePhoto::where('admin_id', $admin->id)->where('vendor_id', $admin->vendor_id)->first();
        if (!$admin_profile) {
            return redirect(route('admin.profile.edit'));
        }
        $line_message = LineMessage::where('vendor_id', $admin->vendor_id)->first();
        $shop_count = Shop::where('vendor_id', $admin->vendor_id)->count();
        $course_count = Course::where('vendor_id', $admin->vendor_id)->count();
        $member_count = Admin::where('vendor_id', $admin->vendor_id)->count();
        $trainer_count = Admin::where('vendor_id', $admin->vendor_id)->where('trainer_role', config('const.TRAINER_ROLE.TRAINER.STATUS'))->count();
//        $business_hour = BusinessHours::selectRaw('DATE_FORMAT(start, "%H:%i") AS start')
//            ->selectRaw('DATE_FORMAT(end, "%H:%i") AS end')
//            ->where('vendor_id', $admin->vendor_id)->first();
//        if ($business_hour) {
//            $start = $business_hour->start;
//            $end = $business_hour->end;
//        }
        $reservation_count = Reservation::where('vendor_id', $admin->vendor_id)
            ->where('admin_id', $admin->id)
            ->where('status', config('const.RESERVATION_STATUS.TENTATIVE.STATUS'))
            ->where('reservation_start', '>', Carbon::now())
            ->count();
        return view('admin.home')->with([
            'member_count' => $member_count,
            'course_count' => $course_count,
            'shop_count' => $shop_count,
            'line_message' => $line_message,
            'reservation_count' => $reservation_count,
            'admin' => $admin,
            'trainer_count' => $trainer_count,
            'start' => $start,
            'end' => $end,
        ]);
    }
}
