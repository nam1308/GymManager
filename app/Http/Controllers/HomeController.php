<?php

namespace App\Http\Controllers;

use App\Models\ChannelJoin;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $channel_join_count = ChannelJoin::where('user_id', Auth::id())->count();
        return view('home')->with([
            'channel_join_count' => $channel_join_count
        ]);
    }
}
