<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserCsvController extends Controller
{

    public function export(Request $request): StreamedResponse
    {
        $response = new StreamedResponse(function () use ($request) {
            $stream = fopen('php://output', 'w');
            // 文字化け回避
            stream_filter_prepend($stream, 'convert.iconv.utf-8/cp932//TRANSLIT');
            // ここでは仮に「products」というテーブルの全データを取得

            $query = User::query();
            if (isset($request->display_name)) {
                $query->where('display_name', 'like', '%' . $request->display_name . '%');
            }
            if (isset($request->name)) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }
            if (isset($request->email)) {
                $query->where('email', 'like', '%' . $request->email . '%');
            }
            $users = $query->orderBy('id', 'DESC');
            if (empty($users[0])) {
                fputcsv($stream, [
                    'データが存在しませんでした。',
                ]);
            } else {
                foreach ($users as $user) {
                    fputcsv($stream, $this->_csvRow($user));
                }
            }
            fclose($stream);
        });
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('content-disposition', 'attachment; filename=会員一覧.csv');
        return $response;
    }

    private function _csvRow($row): array
    {
        return [
            $row->id,
            $row->display_name,
            $row->name,
            $row->phone_number,
            $row->email,
        ];

    }
}
