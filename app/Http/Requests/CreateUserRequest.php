<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'phone_number' => 'required|numeric',
            'email' => 'nullable|email|unique:users|max:255',
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
            'phone_number.required' => '電話番号を入力してください',
        ];
    }
}
