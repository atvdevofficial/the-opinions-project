<?php

namespace App\Http\Controllers;

use App\Http\Requests\Critique\CritiqueDestroyRequest;
use App\Http\Requests\Critique\CritiqueIndexRequest;
use App\Http\Requests\Critique\CritiqueShowRequest;
use App\Http\Requests\Critique\CritiqueStatisticRequest;
use App\Http\Requests\Critique\CritiqueStoreRequest;
use App\Http\Requests\Critique\CritiqueUpdateRequest;
use App\Http\Resources\CritiqueResource;
use App\Models\Critique;
use App\Models\User;
use Illuminate\Http\Request;

class CritiqueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CritiqueIndexRequest $request)
    {
        $critiques = Critique::with('user')->get();

        return CritiqueResource::collection($critiques);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CritiqueStoreRequest $request)
    {
        $user = User::create(array_merge($request->validated(), ['role' => 'CRITIQUE']));
        $critique = Critique::create(array_merge($request->validated(), ['user_id' => $user->id]));

        return new CritiqueResource($critique->load('user'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Critique  $critique
     * @return \Illuminate\Http\Response
     */
    public function show(CritiqueShowRequest $request, Critique $critique)
    {
        return new CritiqueResource($critique->load('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Critique  $critique
     * @return \Illuminate\Http\Response
     */
    public function update(CritiqueUpdateRequest $request, Critique $critique)
    {
        $critique->user->update($request->validated());
        $critique->update($request->validated());

        return new CritiqueResource($critique);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Critique  $critique
     * @return \Illuminate\Http\Response
     */
    public function destroy(CritiqueDestroyRequest $request, Critique $critique)
    {
        $critique->user()->delete();
        $critique->delete();

        return null;
    }

    /**
     * Retrieve the specified critique statistics
     *
     * @param  \App\Models\Critique  $critique
     * @return \Illuminate\Http\Response
     */
    public function statistics(CritiqueStatisticRequest $request, Critique $critique) {
        $topics = $critique->followedTopics()->count() ?? 0;
        $followers = $critique->followers()->count() ?? 0;
        $followings = $critique->followings()->count() ?? 0;

        return response()->json([
            'topics' => $topics,
            'followers' => $followers,
            'followings' => $followings,
        ]);
    }
}
