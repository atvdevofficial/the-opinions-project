<?php

namespace App\Http\Requests\Topic;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TopicUpdateRequest extends FormRequest
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

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $topic = $this->route('topic');

        return [
            'text' => ['required', 'unique:topics,text,' . $topic->id]
        ];
    }
}
