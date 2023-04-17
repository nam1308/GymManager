<?php

namespace App\Rules;

use App\Models\Admin;
use App\Models\Invitation;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class TrainerEmailUnique implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $admin = Auth::user();
        if (Invitation::withTrashed()->where('vendor_id', $admin->vendor_id)->where('email', $value)->first()) {
            return false;
        }
        if (Admin::withTrashed()->where('vendor_id', $admin->vendor_id)->where('email', $value)->first()) {
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'すでに登録済みです';
    }
}
