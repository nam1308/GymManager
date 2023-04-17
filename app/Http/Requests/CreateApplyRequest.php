<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateApplyRequest extends FormRequest
{
    /**
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
            // 会社名
            'company_name' => 'required|string|max:255',
            // 郵便番号
            'postal_code' => 'required|numeric',
            // 都道府県
            'prefecture_id' => 'required|numeric|max:60',
            // 市区町村
            'municipality' => 'required|string|max:255',
            // マンション
            'address_building_name' => 'required|string|max:255',
            // 電話番号
            'phone_number' => 'required|numeric',
            // 名前
            'name' => 'required|string|max:255',
            // メール
            'email' => 'required|email|unique:applies|max:255',
            // メール確認
            'email_confirmation' => 'required|email|max:255',
            // パスワード
            'password' => 'required|min:8|confirmed',
            // パスワード確認
            'password_confirmation' => 'required|min:8',
        ];
    }

    public function message(): array
    {
        return [
            'company_name.required' => '会社名を入力してください',
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
