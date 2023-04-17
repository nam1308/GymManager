<?php

namespace App\Http\Requests;

use App\Models\Admin;
use App\Models\AdminProfilePhoto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateAdminProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $data = [
            // 名前
            'name' => 'required|string|max:255',
            // 自己紹介
            'self_introduction' => 'required|max:1000',
            'trainer_role' => 'required',
        ];
        $profile_image = AdminProfilePhoto::where('admin_id', Auth::id())->first();
        if (!$profile_image) {
            $data['profile_photo'] = 'required|image|file|mimes:jpeg,png,jpg,gif|max:5120';
        }
        return $data;
    }
}
