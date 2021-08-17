<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\ProfileDestroyRequest;
use App\Http\Requests\Profile\ProfileIndexRequest;
use App\Http\Requests\Profile\ProfileShowRequest;
use App\Http\Requests\Profile\ProfileStoreRequest;
use App\Http\Requests\Profile\ProfileUpdateRequest;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProfileIndexRequest $request)
    {
        $profiles = Profile::with('user')->get();

        return ProfileResource::collection($profiles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProfileStoreRequest $request)
    {
        $user = User::create(array_merge($request->validated(), ['role' => 'CRITIQUE']));
        $profile = Profile::create(array_merge($request->validated(), ['user_id' => $user->id]));

        return new ProfileResource($profile->load('user'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(ProfileShowRequest $request, Profile $profile)
    {
        return new ProfileResource($profile->load('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(ProfileUpdateRequest $request, Profile $profile)
    {
        $profile->user->update($request->validated());
        $profile->update($request->validated());

        return new ProfileResource($profile);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProfileDestroyRequest $request, Profile $profile)
    {
        $profile->delete();

        return null;
    }
}
