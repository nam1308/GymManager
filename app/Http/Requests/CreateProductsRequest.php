<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductsRequest extends FormRequest
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
            'client_ip' => 'required',
            'free_term' => 'required|numeric',
            'intial_price' => 'nullable|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'client_ip.required' => '選択に必要なクライアント IP',
            'free_term.required' => '入場には無料期間が必要',
            'free_term.numeric' => '自由時間は数値です',
            'intial_price.numeric' => '価格は数値です',
        ];
    }
}
