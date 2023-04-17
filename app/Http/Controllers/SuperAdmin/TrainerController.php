<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTrainerRequest;
use App\Models\Admin;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class TrainerController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        $query = Admin::query();
        if (isset($request->id)) {
            $query->where('id', $request->id);
        }
        if (isset($request->vendor_id)) {
            $query->where('vendor_id', $request->vendor_id);
        }
        if (isset($request->name)) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if (isset($request->role)) {
            $query->where('role', $request->role);
        }
        if (isset($request->trainer_role)) {
            $query->where('trainer_role', $request->trainer_role);
        }
        if (isset($request->shop)) {
            $query->whereHas('basicSetting', function ($query) use ($request) {
                $query->where('company_name', 'like', '%' . $request->shop . '%');
            });
        }
        $trainers = $query->orderBy('id', 'DESC')->paginate(20, ['*']);
        return view('super-admin.trainer.index')->with([
            'trainers' => $trainers
        ]);
    }

    /**
     * @param string $trainer_id
     * @return \Illuminate\View\View
     */
    public function show(string $trainer_id): View
    {
        $trainer = Admin::find($trainer_id);
        return view('super-admin.trainer.show')->with(['trainer' => $trainer]);
    }

    /**
     * @param $trainer_id
     * @return \Illuminate\View\View
     */
    public function edit($trainer_id): View
    {
        $trainer = Admin::find($trainer_id);
        return view('super-admin.trainer.edit')->with(['trainer' => $trainer]);
    }

    /**
     * @param \App\Http\Requests\UpdateTrainerRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateTrainerRequest $request, $id): RedirectResponse
    {
        try {
            $admin = Admin::find($id);
            $admin->login_id = $request->login_id;
            $admin->name = $request->name;
            //$admin->email = $request->email;
            $admin->role = $request->role;
            if ($request->has('password')) {
                $admin->password = Hash::make($request->password);
            }
            $admin->trainer_role = $request->trainer_role;
            $admin->self_introduction = $request->self_introduction;
            $admin->save();
            return redirect(route('super-admin.trainer.edit', $id))->with('flash_message_success', '保存しました');
        } catch (Throwable $e) {
            return back()->with('flash_message_warning', $e->getMessage())->withInput();
        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            Admin::find($id)->delete();
            return redirect(route('super-admin.trainer'))
                ->with('flash_message_success', '削除しました');
        } catch (Throwable $e) {
            return back()->with('flash_message_danger', '削除失敗');
        }
    }

    /**
     * @param int $trainer_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(int $trainer_id): RedirectResponse
    {
        $admin = Admin::find($trainer_id);
        if ($admin) {
            Auth::guard('admin')->loginUsingId($admin->id, true);
            return redirect(route('admin.home'));
        }
        return redirect(route('super-admin.trainer.login', $trainer_id))
            ->with('flash_message_danger', 'ログイン失敗');
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
            $query = Admin::query();
            if (isset($request->id)) {
                $query->where('id', $request->id);
            }
            if (isset($request->vendor_id)) {
                $query->where('vendor_id', $request->vendor_id);
            }
            if (isset($request->name)) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }
            if (isset($request->role)) {
                $query->where('role', $request->role);
            }
            if (isset($request->trainer_role)) {
                $query->where('trainer_role', $request->trainer_role);
            }
            if (isset($request->shop)) {
                $query->whereHas('baseSetting', function ($query) use ($request) {
                    $query->where('company_name', 'like', '%' . $request->shop . '%');
                });
            }
            $trainers = $query->orderBy('id', 'DESC')->get();
            if (empty($trainers[0])) {
                fputcsv($stream, [
                    'データが存在しませんでした。',
                ]);
            } else {
                fputcsv($stream, ['トレーナID', 'トレーナー名', 'メールアドレス', '店舗', '郵便番号', '都道府県', '住所', '電話番号', 'システム権限', 'トレーナ権限', '登録日']);
                foreach ($trainers as $user) {
                    fputcsv($stream, $this->_csvRow($user));
                }
            }
            fclose($stream);
        });
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('content-disposition', 'attachment; filename=トレーナー一覧.csv');
        return $response;
    }

    /**
     * @param $row
     * @return array
     */
    private function _csvRow($row): array
    {
        try {
            $role = admin_role($row->role);
            $trainer_role = trainer_role($row->trainer_role);
            $prefecture = prefectures($row->basicSetting->prefecture_id);
            return [
                $row->id,
                $row->name,
                $row->email,
                $row->basicSetting->company_name,
                $row->basicSetting->postal_code,
                $prefecture,
                $row->basicSetting->municipality . $row->basicSetting->address_building_name,
                $row->basicSetting->phone_number,
                $role['LABEL'],
                $trainer_role['LABEL'],
                $row->created_at
            ];
        } catch (Exception $e) {
            Log::debug($e->getMessage());
        }
    }
}
