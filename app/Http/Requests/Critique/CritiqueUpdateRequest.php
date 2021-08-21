<?php

namespace App\Http\Requests\Critique;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CritiqueUpdateRequest extends FormRequest
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
            $authenticatedCritique = $authenticatedUser->critique;
            $routeCritique = $this->route('critique');

            if ($routeCritique->id == $authenticatedCritique->id)
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
        $routeCritique = $this->route('critique');

        return [
            'email' => ['required', 'unique:users,email,' . $routeCritique->user->id],
            'password' => ['required', 'string'],
            'name' => ['required', 'string'],
        ];
    }
}