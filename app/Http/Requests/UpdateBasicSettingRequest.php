<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBasicSettingRequest extends FormRequest
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
            'company_name' => 'required|string|max:100',
            'postal_code' => 'required|numeric',
            'prefecture_id' => 'required|numeric',
            'municipality' => 'required|string|max:100',
            'phone_number' => 'required|numeric',
            'business_hours' => 'nullable|string|max:100',
            'other_memo' => 'nullable|max:1000',
            'address_building_name' => 'nullable|string|max:200',
        ];
    }

    /**
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'company_name.required' => '会社名を入力してください',
            'company_name_kana.required' => '会社名（カナ）を入力してください',
            'postal_code.required' => '郵便番号を入力してください',
            'prefecture_id.required' => '都道府県を選択してください',
            'municipality.required' => '市区町村を入力してください',
            'postal_code.numeric' => '郵便番号は半角数字で入力してください。例）1100005',
            'phone_number.required' => '固定電話を入力してください',
            'phone_number.regex' => 'ハイフンを入れて入力してください。例）03-1234-5678',
            'store_name_en.alpha_dash' => '店名(英語表記)は半角英数字で入力してください',
        ];
    }
}
