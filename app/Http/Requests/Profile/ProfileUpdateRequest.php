<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $authenticatedUser = Auth::user();
        $authenticatedUserRole = $authenticatedUser->role;

        if ($authenticatedUserRole == 'ADMINISTRATOR')
            return true;

        if ($authenticatedUserRole == 'CRITIQUE') {
            $authenticatedProfile = $authenticatedUser->profile;
            $routeProfile = $this->route('profile');

            if ($routeProfile->id == $authenticatedProfile->id)
                return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $routeProfile = $this->route('profile');

        return [
            'email' => ['required', 'unique:users,email,' . $routeProfile->user->id],
            'password' => ['required', 'string'],
            'name' => ['required', 'string'],
        ];
    }
}