<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateContactUsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'question_title' => ['required', 'numeric'],
            'content' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'question_title.required' => '質問を選択してください',
            'content.required' => '内容を入力してください',
        ];
    }
}
