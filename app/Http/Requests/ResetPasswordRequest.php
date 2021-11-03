<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
        if (request()->isMethod('POST'))
            return [
                'email' => ['required', 'email', 'exists:users,email']
            ];

        if (request()->isMethod('PUT'))
            return [
                'token' => ['required', 'exists:password_resets,token'],
                'password' => ['required', 'confirmed', 'string', 'min:8'],
            ];
    }
}
