<?php

namespace App\Http\Controllers;
use App\Http\Requests\CritiqueTopic\CritiqueTopicFollowRequest;
use App\Http\Requests\CritiqueTopic\CritiqueTopicIndexRequest;
use App\Http\Requests\CritiqueTopic\CritiqueTopicUnfollowRequest;
use App\Http\Requests\CritiqueTopic\CritiqueTopicUpdateRequest;
use App\Http\Resources\CritiqueResource;
use App\Http\Resources\TopicResource;
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
    public function index(CritiqueTopicIndexRequest $request, Critique $critique)
    {
        return TopicResource::collection($critique->followedTopics);
    }

    /**
     * Update the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(CritiqueTopicUpdateRequest $request, Critique $critique)
    {
        $validatedData = $request->validated();
        $critique->followedTopics()->sync($validatedData['topics']);

        return response()->json(['message' => 'Followed Topics Updated']);
    }

    /**
     * Follow the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function follow(CritiqueTopicFollowRequest $request, Critique $critique, Topic $topic)
    {
        $critique->followedTopics()->syncWithoutDetaching([$topic->id]);

        return response()->json(['message' => 'Topic Followed']);
    }

    /**
     * Unfollow the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function unfollow(CritiqueTopicUnfollowRequest $request, Critique $critique, Topic $topic)
    {
        $critique->followedTopics()->detach([$topic->id]);

        return response()->json(['message' => 'Topic Unfollowed']);
    }
}
