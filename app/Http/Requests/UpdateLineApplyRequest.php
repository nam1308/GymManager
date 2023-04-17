<?php

namespace App\Http\Requests;

use App\Rules\LineUrl;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLineApplyRequest extends FormRequest
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
            'channel_name' => 'required|string|max:255',
            'channel_description' => 'required|max:500',
            'email' => 'required|email|max:255',
            'privacy_policy_url' => 'nullable|url|max:255',
            'store_url' => 'required|url|max:255',
            'terms_of_use_url' => 'nullable|url|max:255',
            'channel_icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:3072',
            'line_uri' => ['nullable', 'string', new LineUrl()],
            'line_uri1' => 'nullable',
        ];
    }
}
