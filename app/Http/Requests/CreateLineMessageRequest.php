<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateLineMessageRequest extends FormRequest
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
            'channel_id' => 'required|string|max:20',
            'channel_secret' => 'required|string|max:500',
            'channel_access_token' => 'required|string|max:500',
            'pr_code' => '|image|mimes:jpeg,png,jpg,gif|max:3072',
            'line_uri1' => 'nullable',
        ];
    }
}
