<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateBusinessHoursRequest;
use App\Models\BusinessHours;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use Exception;
use Auth;

class BusinessHoursController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): View
    {
        $start_time = '';
        $start_minutes = '';
        $end_time = '';
        $end_minutes = '';
        $business_hour = BusinessHours::where('vendor_id', Auth::user()->vendor_id)->first();
        if ($business_hour) {
            $start_time = $business_hour->start ? Carbon::parse($business_hour->start)->format('H') : '';
            $start_minutes = $business_hour->start ? Carbon::parse($business_hour->start)->format('i') : '';
            $end_time = $business_hour->end ? Carbon::parse($business_hour->end)->format('H') : '';
            $end_minutes = $business_hour->end ? Carbon::parse($business_hour->end)->format('i') : '';
        }

        return view('admin.business-hours.edit')->with([
            'start_time' => $start_time,
            'start_minutes' => $start_minutes,
            'end_time' => $end_time,
            'end_minutes' => $end_minutes,
        ]);
    }

    /**
     * @param \App\Http\Requests\UpdateBusinessHoursRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateBusinessHoursRequest $request): RedirectResponse
    {
        try {
            $user = Auth::user();
            if ($request->start_time && $request->start_minutes) {
                $start = $request->start_time . ':' . $request->start_minutes;
            } else {
                $start = NULL;
            }
            if ($request->end_time && $request->end_minutes) {
                $end = $request->end_time . ':' . $request->end_minutes;
            } else {
                $end = NULL;
            }
            $business_hour = BusinessHours::where('vendor_id', $user->vendor_id)->first();
            if ($business_hour) {
                $business_hour->start = $start;
                $business_hour->end = $end;
                $business_hour->save();
            } else {
                BusinessHours::create(
                    [
                        'vendor_id' => $user->vendor_id,
                        'start' => $start,
                        'end' => $end,
                    ]
                );
            }
            return redirect(route('admin.business-hours'))
                ->with('flash_message_success', '保存しました');
        } catch (Exception $e) {
            return back()->with('flash_message_warning', $e->getMessage());
        }
    }
}
