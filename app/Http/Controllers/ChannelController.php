<?php

namespace App\Http\Controllers;

use App\Models\ChannelJoin;
use App\Models\LineMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ChannelController extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $admin = Auth::user();
        $channels = ChannelJoin::where('user_id', $admin->id)->get();
        return view('channel.index')->with([
            'channels' => $channels
        ]);
    }

    /**
     * @param string $vendor_id
     * @return \Illuminate\View\View
     */
    public function show(string $vendor_id): View
    {
        $line_message = LineMessage::where('vendor_id', $vendor_id)->first();
        return view('channel.show')->with(['line_message' => $line_message]);
    }
}
