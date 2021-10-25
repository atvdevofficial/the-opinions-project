<?php

namespace App\Http\Controllers;

use App\Http\Requests\Topic\TopicDestroyRequest;
use App\Http\Requests\Topic\TopicIndexRequest;
use App\Http\Requests\Topic\TopicShowRequest;
use App\Http\Requests\Topic\TopicStoreRequest;
use App\Http\Requests\Topic\TopicUpdateRequest;
use App\Http\Resources\TopicResource;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TopicIndexRequest $request)
    {
        $topics = Topic::all();

        return TopicResource::collection($topics);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TopicStoreRequest $request)
    {
        $topic = Topic::create($request->validated());

        return new TopicResource($topic);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function show(TopicShowRequest $request, Topic $topic)
    {
        return new TopicResource($topic);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function update(TopicUpdateRequest $request, Topic $topic)
    {
        $topic->update($request->validated());

        return new TopicResource($topic);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function destroy(TopicDestroyRequest $request, Topic $topic)
    {
        $topic->delete();

        return null;
    }

    /**
     * Retrieve top / trending topics
     * by number of opinions
     *
     * @return \Illuminate\Http\Response
     */
    public function topTrending() {
        $topics = Topic::select('text', DB::raw('COUNT(text) as total'))->join('opinion_topic', 'opinion_topic.topic_id', 'topics.id')->groupBy('text')->orderBy('total')->get();

        return response()->json($topics);
    }
}
