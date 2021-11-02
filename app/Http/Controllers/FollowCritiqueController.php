<?php

namespace App\Http\Controllers;

use App\Http\Requests\FollowCritique\FollowCritiqueFollowRequest;
use App\Http\Requests\FollowCritique\FollowCritiqueIndexRequest;
use App\Http\Requests\FollowCritique\FollowCritiqueUnfollowRequest;
use App\Models\Critique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowCritiqueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FollowCritiqueIndexRequest $request)
    {
        $authenticatedUser = Auth::user();
        $authenticatedCritique = $authenticatedUser->critique;

        $followers = $authenticatedCritique->followers()
                ->paginate(10, ['*'], 'followers')
                ->appends(request()->except(['followers']));

        $followings = $authenticatedCritique->followings()
                ->paginate(10, ['*'], 'followings')
                ->appends(request()->except(['followers']));

        return response([
            'followers' => $followers,
            'followings' => $followings,
        ]);
    }

    /**
     * Follow the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function follow(FollowCritiqueFollowRequest $request, Critique $critique)
    {
        $authenticatedUser = Auth::user();
        $authenticatedCritique = $authenticatedUser->critique;

        $authenticatedCritique->followings()->syncWithoutDetaching([$critique->id]);

        return response()->json(['message' => 'Critique Followed']);
    }

    /**
     * Unfollow the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function unfollow(FollowCritiqueUnfollowRequest $request, Critique $critique)
    {
        $authenticatedUser = Auth::user();
        $authenticatedCritique = $authenticatedUser->critique;

        $authenticatedCritique->followings()->detach([$critique->id]);

        return response()->json(['message' => 'Critique Unfollowed']);
    }
}
