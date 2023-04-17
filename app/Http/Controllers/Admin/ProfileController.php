<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAdminProfileRequest;
use App\Models\Admin;
use App\Models\AdminProfilePhoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Intervention\Image\Facades\Image;
use Throwable;

class ProfileController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function edit(): View
    {
        $admin = Auth::user();
        $profile_photos = AdminProfilePhoto::where('admin_id', $admin->id)->get();
        return view('admin.profile.edit')->with([
            'admin' => $admin,
            'profile_photos' => $profile_photos,
        ]);
    }

    /**
     * @param \App\Http\Requests\UpdateAdminProfileRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateAdminProfileRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $admin = Admin::find(Auth::id());
            $admin->name = $request->name;
            $admin->self_introduction = $request->self_introduction;
            $admin->trainer_role = $request->trainer_role;
            $admin->save();
            // 画像があれば
            if ($request->profile_photo) {
                // 古い写真があるか？
                $admin_photo = AdminProfilePhoto::where(['vendor_id' => $admin->vendor_id, 'admin_id' => Auth::id()])->get();
                // 削除
                if ($admin_photo) {
                    foreach ($admin_photo as $photo) {
                        $photo->delete();
                        $path = storage_path('app/public/assets/images/admin/' . $admin->vendor_id . '/profile/' . Auth::id() . '/' . $photo->file);
                        if (file_exists($path)) {
                            $delete_file_path = 'public/assets/images/admin/' . $admin->vendor_id . '/profile/' . Auth::id() . '/' . $photo->file;
                            Storage::delete($delete_file_path);
                        }
                    }
                }
                //画像保存先作成
                $photoData = $request->profile_photo->store('public/assets/images/admin/' . $admin->vendor_id . '/profile/' . $admin->id);
                //画像ファイル名取得
                $name = pathinfo($photoData, PATHINFO_BASENAME);
                //データベース保存
                AdminProfilePhoto::create([
                    'vendor_id' => $admin->vendor_id,
                    'admin_id' => Auth::id(),
                    'file' => $name,
                ])->save();
                // パス
                $path = storage_path('app/public/assets/images/admin/' . $admin->vendor_id . '/profile/' . $admin->id . '/' . $name);
                if (file_exists($path)) {
                    // 加工
                    $profile_photo = Image::make($path);
                    $profile_photo->orientate();
                    $profile_photo->fit(600, 600, function ($constraint) {
                        // 縦横比を保持したままにする
                        $constraint->aspectRatio();
                        // 小さい画像は大きくしない
                        $constraint->upsize();
                    })->save(storage_path('app/public/assets/images/admin/' . $admin->vendor_id . '/profile/' . $admin->id . '/' . $name));
                }
            }
            DB::commit();
            return redirect(route('admin.home'))->with('flash_message_success', 'プロフィールを保存しました。');
        } catch (Throwable $e) {
            return back()->with('flash_message_warning', $e->getMessage());
        }
    }
}
