<?php

namespace App\Http\Controllers;
use App\Http\Requests\CritiqueTopic\CritiqueTopicFollowRequest;
use App\Http\Requests\CritiqueTopic\CritiqueTopicIndexRequest;
use App\Http\Requests\CritiqueTopic\CritiqueTopicUnfollowRequest;
use App\Http\Resources\CritiqueResource;
use App\Models\Critique;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CritiqueTopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CritiqueTopicIndexRequest $request)
    {
        $authenticatedUser = Auth::user();
        $authenticatedCritique = $authenticatedUser->critique;

        return CritiqueResource::collection($authenticatedCritique->followedTopics);
    }

    /**
     * Follow the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function follow(CritiqueTopicFollowRequest $request, Topic $topic)
    {
        $authenticatedUser = Auth::user();
        $authenticatedCritique = $authenticatedUser->critique;

        $authenticatedCritique->followedTopics()->syncWithoutDetaching([$topic->id]);

        return response(['message' => 'Critique Followed']);
    }

    /**
     * Unfollow the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function unfollow(CritiqueTopicUnfollowRequest $request, Topic $topic)
    {
        $authenticatedUser = Auth::user();
        $authenticatedCritique = $authenticatedUser->critique;

        $authenticatedCritique->followedTopics()->detach([$topic->id]);

        return response(['message' => 'Critique Unfollowed']);
    }
}
