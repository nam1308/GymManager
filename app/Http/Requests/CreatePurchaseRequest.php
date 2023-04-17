<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePurchaseRequest extends FormRequest
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
            // 商品ID
            'product_code' => ['required', 'string', 'max:50'],
            // 姓
            'sei' => ['required', 'string', 'max:50'],
            // 英
            'mei' => ['required', 'string', 'max:50'],
            // セイ
            'sei_kana' => 'required|string|max:50|regex:/^[ア-ン゛゜ァ-ォャ-ョー]+$/u',
            // メイ
            'mei_kana' => 'required|string|max:50|regex:/^[ア-ン゛゜ァ-ォャ-ョー]+$/u',
            // メールアドレス
            'email' => 'required|email|max:255|unique:users|confirmed',
            // メールアドレス（確認）
            'email_confirmation' => 'required|email|max:255',
            // 誕生日（年）
            'birthday_year' => 'required|numeric',
            // 誕生日（月）
            'birthday_month' => 'required|numeric',
            // 誕生日（日）
            'birthday_day' => 'required|numeric',
            // 性別
            'gender_id' => 'required|filled',
            // 固定電話
            'phone_number_1' => 'required|numeric|regex:/\A0\d{1,3}\z/',
            'phone_number_2' => 'required|numeric|digits_between:2,4',
            'phone_number_3' => 'required|numeric|digits:4',
            // 携帯
            'cellphone_number_1' => 'required|numeric',
            'cellphone_number_2' => 'required|numeric',
            'cellphone_number_3' => 'required|numeric',
            // 郵便番号
            'postal_code' => 'required|numeric',
            // 都道府県
            'prefecture_id' => 'required|numeric',
            // 市区町村
            'municipality' => ['required', 'string', 'max:100'],
            // ビル・マンション
            'address_building_name' => 'nullable|string|max:200',
            // パスワード
            'password' => 'required|min:8|confirmed',
            // パスワード確認
            'password_confirmation' => 'required|min:8',
            // 支払い方法
            'payment_type_id' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return parent::messages([
            // 名前
            'sei.required' => ':attributeを入力してください',
            'mei.required' => ':attributeを入力してください',
            'sei_kana.required' => ':attributeを入力してください',
            'mei_kana.required' => ':attributeを入力してください',
            'sei_kana.regex' => ':attributeはカタカナで入力してください',
            'mei_kana.regex' => ':attributeはカタカナで入力してください',
            // メールアドレス
            'email.required' => ':attributeを入力してください',
            'email_confirmation.required' => ':attributeを入力してください',
            'email_confirmation.confirmed' => ':attributeと:attributeが一致しません',
            // 誕生日
            'birthday_year.required' => ':attributeを選択してください',
            'birthday_month.required' => ':attributeを選択してください',
            'birthday_day.required' => ':attributeを選択してください',
            // 性別
            'gender_id.required' => ':attributeを選択してください',
            // 住所
            'postal_code.required' => ':attributeを入力してください',
            'prefecture_id.required' => '都道府県を選択してください',
            'municipality.required' => '市区町村を入力してください',
            // パスワード
            'password.required' => ':attributeを入力してください',
            'password.min' => 'パスワードは8文字以上入力してください',
            'postal_code.numeric' => '郵便番号は半角数字で入力してください。例）1100005',
            // 電話番号
            'phone_number_1.*' => ':attributeを入力してください',
            'phone_number_2.*' => ':attribute入力してください',
            'phone_number_3.*' => ':attributeを入力してください',
            // 携帯
            'cellphone_number_1.*' => ':attributeを入力してください',
            'cellphone_number_2.*' => ':attribute入力してください',
            'cellphone_number_3.*' => ':attributeを入力してください',
        ]);
    }
}
