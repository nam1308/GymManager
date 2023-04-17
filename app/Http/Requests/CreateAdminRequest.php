<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAdminRequest extends FormRequest
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
        return [
            // 名前
            'name' => 'required|string|max:255',
            // 自己紹介
            'self_introduction' => 'required|max:1000',
            // パスワード
            'password' => 'required|min:8|confirmed',
            // パスワード確認
            'password_confirmation' => 'required|min:8',
            // プロフィール画像
            'profile_photo' => 'required|image|file|mimes:jpeg,png,jpg,gif|max:5072',
        ];
    }

    public function message(): array
    {
        return [
            'company_name.required' => '会社名を入力してください',
            'self_introduction.required' => '自己紹介を入力してください',
            'postal_code.required' => '郵便番号を入力してください',
            'prefecture_id.required' => '都道府県を選択してください',
            'municipality.required' => '市区町村を入力してください',
            'phone_number.required' => '電話番号を入力してください',
            'name.required' => 'お名前を入力してください',
            'email.required' => 'メールアドレスを入力してください',
            'password.min' => 'パスワードは8文字以上入力してください',
            'postal_code.numeric' => '郵便番号は半角数字で入力してください。例）1100005',
        ];
    }
}
