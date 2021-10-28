<?php

namespace App\Http\Requests\CritiqueTopic;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CritiqueTopicUpdateRequest extends FormRequest
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
            'topics' => ['required', 'array', 'min:1'],
            'topics.*' => ['required', 'exists:topics,id'],
        ];
    }
}
