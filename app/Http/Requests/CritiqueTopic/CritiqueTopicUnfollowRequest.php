<?php

namespace App\Http\Requests\CritiqueTopic;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CritiqueTopicUnfollowRequest extends FormRequest
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
            $authenticatedCritique = $authenticatedUser->critique;
            $routeCritique = $this->route('critique');

            if ($authenticatedCritique->id = $routeCritique->id)
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
