<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedRequest;
use App\Http\Resources\OpinionResource;
use App\Models\Opinion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedController extends Controller
{
    public function __invoke(FeedRequest $request)
    {
        $authenticatedUser = Auth::user();
        $authenticatedCritique = $authenticatedUser->critique;
        $authenticatedCritiqueId = $authenticatedCritique->id;


        $opinions = Opinion::join('follow_critique', 'follow_critique.followed_id', 'opinions.critique_id')->select('opinions.*')->where([['follow_critique.follower_id', $authenticatedCritiqueId]])->orderBy('opinions.created_at', 'DESC')->with('critique', 'topics')->paginate(10);

        return OpinionResource::collection($opinions);
    }
}
