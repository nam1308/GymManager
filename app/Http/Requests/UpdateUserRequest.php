<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name' => 'required|string|max:50',
            'name_kana' => 'required|string|max:50|regex:/^[ア-ン゛゜ァ-ォャ-ョー]+$/u',
            'phone_number' => 'required|string|max:50',
            'birthday_year' => 'required|numeric',
            'birthday_month' => 'required|numeric',
            'birthday_day' => 'required|numeric',
            'gender_id' => 'required|filled',
        ];
    }

    /**
     * バリデーションエラー
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'name.required' => '名前を入力してください',
            'name_kana.required' => '名前（カタカナ）を入力してください',
            'name_kana.regex' => '名前（カタカナ）はカタカナで入力してください',
            'phone_number.required' => '電話番号を入力してください',
            'birthday_year.required' => '生年月日（年）を選択してください',
            'birthday_month.required' => '生年月日（月）を選択してください',
            'birthday_day.required' => '生年月日（日）を選択してください',
            'gender_id.required' => '性別を選択してください',
        ];
    }
}
