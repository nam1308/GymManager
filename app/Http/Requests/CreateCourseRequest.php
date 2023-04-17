<?php

namespace App\Http\Requests;

use App\Rules\CourseTime;
use Illuminate\Foundation\Http\FormRequest;

class CreateCourseRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'price' => 'required|regex:/^[0-9]+$/',
            'course_time' => 'required_without:course_minutes',
            'course_minutes' => '',
            'contents' => 'required|max:5000',
        ];
    }

    /**
     * バリデーションエラー
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'name.required' => 'メニュー名を入力してください',
            'course_time.regex' => ':attributeを選択してください',
            'course_minutes.regex' => ':attributeを選択してください',
            'price.regex' => '価格は半角数字で入力してください',
        ];
    }
}
