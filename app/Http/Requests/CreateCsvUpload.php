<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCsvUpload extends FormRequest
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
            'csv' => 'required|file|mimetypes:text/plain|mimes:csv,txt',
        ];
    }

    /**
     *
     * @return array
     */
    public function messages()
    {
        return [
            'csv.required' => 'ファイルを選択してください。',
            'csv.file' => 'ファイルアップロードに失敗しました。',
            'csv.mimetypes' => 'ファイル形式が不正です。',
            'csv.mimes' => 'ファイル拡張子が異なります。',
        ];
    }
}
