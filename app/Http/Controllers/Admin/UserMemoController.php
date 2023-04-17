<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChannelJoin;
use App\Models\User;
use App\Models\UserMemo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class UserMemoController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param string $user_id
     * @return array
     */
    public function update(Request $request, string $user_id): array
    {
        try {
            $admin = Auth::user();
            $channel = ChannelJoin::where('user_id', $user_id)->where('vendor_id', $admin->vendor_id)->first();
            if (!$channel) {
                throw new Exception('申込データーが見つかりません');
            }
            $user = User::find($user_id);
            if (!$user) {
                throw new Exception('会員が見つかりません');
            }
            $data = [
                'user_id' => $user_id,
                'vendor_id' => $admin->vendor_id,
                'memo' => $request->memo,
            ];
            UserMemo::updateOrCreate(['user_id' => $user_id, 'vendor_id' => $admin->vendor_id], $data);
            return [
                'status' => true,
                'message' => '保存しました',
            ];
        } catch (Throwable $e) {
            Log::debug($e->getMessage());
            return [
                'status' => true,
                'message' => '保存失敗しました',
            ];
        }
    }
}
