<?php

namespace App\Http\Requests\Opinion;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class OpinionDestroyRequest extends FormRequest
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
            $routeOpinion = $this->route('opinion');

            if ($routeOpinion->profile->id == $authenticatedProfile->id)
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
