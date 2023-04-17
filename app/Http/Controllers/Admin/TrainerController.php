<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminProfilePhoto;
use App\Models\Invitation;
use App\Models\Product;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Mail as FacadesMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class TrainerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $stripe;


    public function __construct(StripeController $stripe)
    {
        $this->stripe = $stripe;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        // トレーナー取得
        $trainers = Admin::adminVendorId()->orderBy('id', 'DESC')->paginate(30, ['*']);
        return view('admin.trainer.index')->with([
            'trainers' => $trainers,
        ]);
    }

    /**
     * @return Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('admin.trainer.create');
    }

    /**
     * @param int $admin_id
     * @return \Illuminate\View\View
     */
    public function show(int $admin_id): View
    {
        // 自分
        $admin = Auth::guard('admin')->user();
        // トレーナー
        $trainer = Admin::getTrainer($admin->vendor_id, $admin_id);
        return view('admin.trainer.show')->with([
            'trainer' => $trainer,
            'admin' => $admin,
        ]);
    }

    /**
     * @param $admin_id
     * @return \Illuminate\View\View
     */
    public function edit($admin_id): View
    {
        // 自分
        $admin = Auth::guard('admin')->user();
        // トレーナー
        $trainer = Admin::getTrainer($admin->vendor_id, $admin_id);
        return view('admin.trainer.edit')->with([
            'trainer' => $trainer,
            'admin' => $admin,
        ]);
    }

    /**
     * @param $admin_id
     * @return \Illuminate\View\View
     */
    public function profileEdit($admin_id): View
    {
        // 自分
        $admin = Auth::guard('admin')->user();
        // トレーナー
        $trainer = Admin::getTrainer($admin->vendor_id, $admin_id);
        $login_id = $trainer['login_id'];
        $profile_photos = AdminProfilePhoto::where('login_id', $login_id)->get();
        return view('admin.trainer.edit')->with([
            'trainer' => $trainer,
            'admin' => $admin,
            'profile_photos' => $profile_photos
        ]);
    }

    /**
     * トレーナー
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function changeTrainerRole(Request $request): array
    {
        Log::debug($request->all());
        try {
            $admin = Auth::user();
            $trainer = Admin::getTrainer($admin->vendor_id, $request->trainer_id);
            $trainer->trainer_role = $request->trainer_role;
            $trainer->save();
            return [
                'status' => true,
                'message' => '更新しました',
            ];
        } catch (Throwable $e) {
            return [
                'status' => false,
                'message' => '更新失敗',
            ];
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */


    public function destroy(Request $request): array
    {
        $data = [
            'status' => false,
            'changePlan' =>false,
            'message' => '',
            'url' => route('admin.trainer'),
        ];

        try {
            $trainer = Admin::find($request->delete_trainer_id);

            if (!$trainer) {
                throw new Exception('トレーナーが見つかりません');
            }
            if ($trainer->vendor_id != $request->vendor_id) {
                throw new Exception('トレーナーを削除する権限がありません');
            }
            if ($trainer->role == config('const.TRAINER_ROLE.TRAINER.STATUS')) {
                throw new Exception('管理者を削除することはできません');
            }

            $date = Carbon::now();
            $trainer->deleted_at = $date;
            $trainer->save();

            // check down plan
            $trainers_count = Admin::adminVendorId()->count();
            $invitation_count = Invitation::where('vendor_id', $request->vendor_id)->count();

            $before_product =  checkPlan();
            if( $trainers_count + $invitation_count === $before_product['trainer_count']  ){
                $data['status'] = true;
                $data['changePlan'] = true;
                return $data;
            }

            $data['status'] = true;
            $data['message'] = '削除しました';
            return $data;
        } catch (Exception $e) {
            return $data;
        }
    }

    public function changePlan(Request $request){

        $before_product =  checkPlan();

        $admin = Auth::guard('admin')->user();
        //update datetime
        Product::where('vendor_id', $admin->vendor_id)->update(['reference_date' => Carbon::now()]);

        // update
        $this->stripe->change($request, $before_product['id']);

        return redirect(route('admin.trainer'))->with('flash_message_success', 'プランが変更されました。ご登録のメールをご確認ください');
    }

}
