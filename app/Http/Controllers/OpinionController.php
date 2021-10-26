<?php

namespace App\Http\Controllers;

use App\Http\Requests\Opinion\OpinionDestroyRequest;
use App\Http\Requests\Opinion\OpinionLikeRequest;
use App\Http\Requests\Opinion\OpinionShowRequest;
use App\Http\Requests\Opinion\OpinionUnlikeRequest;
use App\Http\Requests\Opinion\OpinionUpdateRequest;
use App\Http\Resources\OpinionResource;
use App\Models\Opinion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpinionController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Opinion  $opinion
     * @return \Illuminate\Http\Response
     */
    public function show(OpinionShowRequest $request, Opinion $opinion)
    {
        return new OpinionResource($opinion);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Opinion  $opinion
     * @return \Illuminate\Http\Response
     */
    public function update(OpinionUpdateRequest $request, Opinion $opinion)
    {
        $opinion->update($request->validated());
        $opinion->topics()->sync($request->validated()['topics']);

        return new OpinionResource($opinion);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Opinion  $opinion
     * @return \Illuminate\Http\Response
     */
    public function destroy(OpinionDestroyRequest $request, Opinion $opinion)
    {
        $opinion->delete();

        return null;
    }

    /**
     * Like specified opinion
     *
     * * @param  \App\Models\Opinion  $opinion
     * @return \Illuminate\Http\Response
     */
    public function like(OpinionLikeRequest $request, Opinion $opinion) {
        $authenticatedUser = Auth::user();
        $authenticatedCritique = $authenticatedUser->critique;

        $opinion->likers()->sync([$authenticatedCritique->id], false);

        return response()->json(['message' => 'Opinion Liked']);
    }

    /**
     * Unlike specified opinion
     *
     * * @param  \App\Models\Opinion  $opinion
     * @return \Illuminate\Http\Response
     */
    public function unlike(OpinionUnlikeRequest $request, Opinion $opinion) {
        $authenticatedUser = Auth::user();
        $authenticatedCritique = $authenticatedUser->critique;

        $opinion->likers()->detach([$authenticatedCritique->id]);

        return response()->json(['message' => 'Opinion Unliked']);
    }
}
