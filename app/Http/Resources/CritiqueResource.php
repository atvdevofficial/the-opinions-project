<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CritiqueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $authenticatedUser = Auth::user();
        $authenticatedUserRole = $authenticatedUser->role;

        if ($authenticatedUserRole == 'ADMINISTRATOR') {
            return parent::toArray($request);

        } else if ($authenticatedUserRole == 'CRITIQUE') {
            $authenticatedCritique = $authenticatedUser->critique;
            $critiqueResourseId = $this->id;

            if ($critiqueResourseId == $authenticatedCritique->id)
                return parent::toArray($request);
            else
                return Arr::except(parent::toArray($request), ['user']);
        } else {
            return Arr::except(parent::toArray($request), ['user']);
        }
    }
}
