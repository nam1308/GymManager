<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateShopRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'url' => ['nullable', 'url', 'max:255'],
            'phone_number' => ['nullable', 'regex:/^[0-9]+$/'],
            'postal_code' => ['required', 'min:7', 'max:7', 'regex:/^[0-9]+$/'],
            'prefecture_id' => ['required', 'integer', 'max:50'],
            'municipality' => ['required', 'string', 'max:100'],
            'address_building_name' => ['nullable', 'string', 'max:100'],
            'contents' => ['nullable', 'max:1000'],
        ];
    }

    /**
     * バリデーションエラー
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'name.required' => '店舗名を入力してください',
            'phone_number.regex' => '電話番号は半角数字で入力してください',
            'phone_number.required' => '電話番号を入力してください',
            'postal_code.required' => '郵便番号を入力してください',
            'url.url' => 'URLを正しく入力してください',
            'postal_code.max' => '郵便番号を正しく入力してください'
        ];
    }
}
