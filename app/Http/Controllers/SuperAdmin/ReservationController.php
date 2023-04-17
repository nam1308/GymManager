<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReservationController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        $query = Reservation::query();
        if (isset($request->id)) {
            $query->where('id', $request->id);
        }
        if (isset($request->status)) {
            $query->where('status', $request->status);
        }
        if (isset($request->user_name)) {
            $query->whereHas('user', function ($query) use ($request) {
                $query->where('users.name', 'like', '%' . $request->user_name . '%');
            });
        }
        if (isset($request->trainer_name)) {
            $query->whereHas('admin', function ($query) use ($request) {
                $query->where('admins.name', 'like', '%' . $request->trainer_name . '%');
            });
        }
        if (isset($request->course)) {
            $query->whereHas('course', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->course . '%');
            });
        }
        if (isset($request->shop)) {
            $query->whereHas('shop', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->shop . '%');
            });
        }
        $reservations = $query->orderBy('reservation_start', 'DESC')->paginate(20, ['*']);
        return view('super-admin.reservation.index', compact('reservations'));
    }

    /**
     * CSV
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export(Request $request): StreamedResponse
    {
        $response = new StreamedResponse(function () use ($request) {
            $stream = fopen('php://output', 'w');
            stream_filter_prepend($stream, 'convert.iconv.utf-8/cp932//TRANSLIT');
            $query = Reservation::query();
            if (isset($request->id)) {
                $query->where('id', $request->id);
            }
            if (isset($request->status)) {
                $query->where('status', $request->status);
            }
            if (isset($request->user_name)) {
                $query->whereHas('user', function ($query) use ($request) {
                    $query->where('users.name', 'like', '%' . $request->user_name . '%');
                });
            }
            if (isset($request->trainer_name)) {
                $query->whereHas('admin', function ($query) use ($request) {
                    $query->where('admins.name', 'like', '%' . $request->trainer_name . '%');
                });
            }
            if (isset($request->course)) {
                $query->whereHas('course', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->course . '%');
                });
            }
            if (isset($request->shop)) {
                $query->whereHas('shop', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->shop . '%');
                });
            }
            $reservations = $query->select()->orderBy('id', 'DESC')->get();
            if (empty($reservations[0])) {
                fputcsv($stream, [
                    'データが存在しませんでした。',
                ]);
            } else {
                fputcsv($stream, ['予約ID', '会員ID', '会員', 'トレーナ名', 'ステータス', 'メニュー', '店舗', '開始日', '完了日', '備考']);
                foreach ($reservations as $user) {
                    fputcsv($stream, $this->_csvRow($user));
                }
            }
            fclose($stream);
        });
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('content-disposition', 'attachment; filename=予約一覧.csv');
        return $response;
    }

    /**
     * @param $row
     * @return array
     */
    private function _csvRow($row): array
    {
        try {
            $status = reservation_status($row->status);
            return [
                $row->id,
                $row->user_id,
                $row->user->name,
                $row->admin->name,
                $status['LABEL'],
                $row->course->name,
                $row->shop->name,
                $row->reservation_start,
                $row->reservation_end,
                $row->note,
            ];
        } catch (Exception $e) {
            Log::debug($e->getMessage());
        }
    }
}
