<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Http\Resources\CritiqueResource;
use App\Models\Critique;
use App\Models\User;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function __invoke(RegistrationRequest $request)
    {
        $user = User::create(array_merge($request->validated(), ['role' => 'CRITIQUE']));
        $critique = Critique::create(array_merge($request->validated(), ['user_id' => $user->id]));

        return response()->json(['message' => 'Account creation successful']);
    }
}
