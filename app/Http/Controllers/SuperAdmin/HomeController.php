<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Apply;
use App\Models\LineMessage;

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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $apply_count = Apply::count();
        $line_message_count = LineMessage::where('status', config('const.LINE_STATUS.APPLYING.STATUS'))->count();
        return view('super-admin.home')->with([
            'apply_count' => $apply_count,
            'line_message_count' => $line_message_count,
        ]);
    }
}
