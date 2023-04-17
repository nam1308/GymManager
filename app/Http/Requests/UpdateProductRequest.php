<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
     *
     * @return string[][]
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:255'],
            'price' => ['nullable', 'numeric'],
            'client_ip' => ['required', 'numeric'],
            'purchase_count' => ['nullable', 'numeric'],
            'free_term' => ['nullable', 'numeric'],
        ];
    }

    /**
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => '商品名を入力してください',
            'client_ip.required' => 'クライアントIPを入力してください',
            'name.max' => '商品名は255文字以内で入力してください',
            'price.numeric' => '価格は半角数字で入力してください',
            'client_ip.numeric' => 'クライアントIPをは半角数字で入力してください',
            'purchase_count.numeric' => '購入数は半角数字で入力してください',
            'free_term.numeric' => '無料期間は半角数字で入力してください',
        ];
    }
}
