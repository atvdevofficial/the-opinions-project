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
        $topic = $request->input('topic') ?? null;
        $critique = $request->input('critique') ?? null;

        if ($topic != 'null' and $topic != null and $topic != '') {
            // Opinions by topic
            $opinions = Opinion::join('opinion_topic', 'opinion_topic.opinion_id', 'opinions.id')
                ->join('topics', 'topics.id', 'opinion_topic.topic_id')
                ->select('opinions.*')
                ->where('topics.text', 'LIKE', "%${topic}%")
                ->orderBy('created_at', 'DESC')
                ->with('critique', 'topics')
                ->paginate(10);
            $opinions->appends(['topic' => $topic]);
        } elseif ($topic != 'null' and $critique != null and $critique != '') {
            // Opinions by critique
            $opinions = Opinion::join('critiques', 'critiques.id', 'opinions.critique_id')
                ->where('critiques.username', $critique)
                ->select('opinions.*')
                ->orderBy('created_at', 'DESC')
                ->with('critique', 'topics')
                ->paginate(10);
            $opinions->appends(['critique' => $critique]);
        } else {
            $authenticatedUser = Auth::user();
            $authenticatedCritique = $authenticatedUser->critique;
            $authenticatedCritiqueId = $authenticatedCritique->id;

            // Opinions by followed topics and followed critiques
            $opinions = Opinion::join('opinion_topic', 'opinion_topic.opinion_id', 'opinions.id')
                ->join('critique_topic', 'critique_topic.topic_id', 'opinion_topic.topic_id')
                ->select('opinions.*')
                ->where('critique_topic.critique_id', $authenticatedCritiqueId)
                ->where('opinions.critique_id', '<>', $authenticatedCritiqueId)
                ->union(
                    // Opinions of followed critiques
                    Opinion::join('follow_critique', 'follow_critique.followed_id', 'opinions.critique_id')
                        ->select('opinions.*')
                        ->where([['follow_critique.follower_id', $authenticatedCritiqueId]])
                )
                ->orderBy('created_at', 'DESC')
                ->with('critique', 'topics')
                ->paginate(10);
        }

        return OpinionResource::collection($opinions);
    }
}
