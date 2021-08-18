<?php

namespace App\Http\Requests\ProfileOpinion;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProfileOpinionIndexRequest extends FormRequest
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

        if ($authenticatedUserRole == 'CRITIQUE') {
            $authenticatedProfile = $authenticatedUser->profile;
            $routeProfile = $this->route('profile');

            if ($authenticatedProfile->id == $routeProfile->id)
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
        return [
            //
        ];
    }
}
