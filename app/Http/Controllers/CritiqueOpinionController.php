<?php

namespace App\Http\Controllers;

use App\Http\Requests\CritiqueOpinion\CritiqueOpinionIndexRequest;
use App\Http\Requests\CritiqueOpinion\CritiqueOpinionStoreRequest;
use App\Http\Resources\OpinionResource;
use App\Models\Critique;
use App\Models\User;
use App\Notifications\OpinionNotification;
use Illuminate\Support\Facades\Notification;

class CritiqueOpinionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CritiqueOpinionIndexRequest $request, Critique $critique)
    {
        $opinions = $critique->opinions()->orderBy('created_at', 'desc')->with('critique', 'topics')->paginate(10);

        return OpinionResource::collection($opinions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CritiqueOpinionStoreRequest $request, Critique $critique)
    {
        $opinion = $critique->opinions()->create($request->validated());
        $opinion->topics()->sync($request->validated()['topics']);

        /**
         * Opinion Notifications
         * Notify critique followers and topics followers
         */

        // Critique followers
        $critiqueFollowers = User::join('critiques', 'critiques.user_id', 'users.id')
            ->join('follow_critique', 'follow_critique.follower_id', 'critiques.id')
            ->select('users.*')
            ->where([['follow_critique.followed_id', $opinion->critique_id]])
            ->get();

        // Topic followers
        $topicFollowers = User::join('critiques', 'critiques.user_id', 'users.id')
            ->join('critique_topic', 'critique_topic.critique_id', 'critiques.id')
            ->join('opinion_topic', 'opinion_topic.topic_id', 'critique_topic.topic_id')
            ->select('users.*')
            ->where([
                ['opinion_topic.opinion_id', $opinion->id],
                ['critiques.id', '<>', $opinion->critique_id]
            ])
            ->get();

        // Merge followers
        $usersToNotify = $critiqueFollowers->merge($topicFollowers);

        // Notify followers
        Notification::send($usersToNotify, new OpinionNotification($opinion, "A new opinion was submitted."));


        return new OpinionResource($opinion->load('critique', 'topics'));
    }
}
