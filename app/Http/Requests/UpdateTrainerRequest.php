<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTrainerRequest extends FormRequest
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
            'login_id' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'password' => 'nullable|min:8',
            'self_introduction' => 'nullable|max:1000',
            'role' => 'nullable',
            'trainer_role' => 'nullable',
        ];
    }
}
