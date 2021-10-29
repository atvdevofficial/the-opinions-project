<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedRequest;
use App\Http\Resources\OpinionResource;
use App\Models\Opinion;
use Illuminate\Support\Facades\Auth;

class FeedController extends Controller
{
    public function __invoke(FeedRequest $request)
    {
        $authenticatedUser = Auth::user();
        $authenticatedCritique = $authenticatedUser->critique;
        $authenticatedCritiqueId = $authenticatedCritique->id;

        // Opinions by followed critiques
        $opinionsByFollowedCritiques = Opinion::join('follow_critique', 'follow_critique.followed_id', 'opinions.critique_id')
            ->select('opinions.*')
            ->where([['follow_critique.follower_id', $authenticatedCritiqueId]])
            ->orderBy('opinions.created_at', 'DESC')
            ->with('critique', 'topics')
            ->paginate(10);

        // Opinions by followed topics
        $opinionsByFollowedTopics = Opinion::join('opinion_topic', 'opinion_topic.opinion_id', 'opinions.id')
            ->join('critique_topic', 'critique_topic.topic_id', 'opinion_topic.topic_id')
            ->select('opinions.*')
            ->where('critique_topic.critique_id', $authenticatedCritiqueId)
            ->where('opinions.critique_id', '<>', $authenticatedCritiqueId)
            ->orderBy('opinions.created_at', 'DESC')
            ->with('critique', 'topics')
            ->paginate(10);

        return OpinionResource::collection($opinionsByFollowedCritiques);
    }
}
