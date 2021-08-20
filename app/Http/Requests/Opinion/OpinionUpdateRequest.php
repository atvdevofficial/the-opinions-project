<?php

namespace App\Http\Requests\Opinion;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class OpinionUpdateRequest extends FormRequest
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
            $routeOpinion = $this->route('opinion');

            if ($routeOpinion->critique->id == $authenticatedCritique->id)
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
            'text' => ['required', 'string', 'max:500'],
            'is_public' => ['required', 'boolean'],

            'topics' => ['required', 'array', 'min:1'],
            'topics.*' => ['required', 'exists:topics,id']
        ];
    }
}
