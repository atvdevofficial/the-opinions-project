<?php

namespace App\Http\Controllers;

use App\Http\Requests\CritiqueOpinion\CritiqueOpinionIndexRequest;
use App\Http\Requests\CritiqueOpinion\CritiqueOpinionStoreRequest;
use App\Http\Resources\OpinionResource;
use App\Models\Critique;
use Illuminate\Http\Request;

class CritiqueOpinionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CritiqueOpinionIndexRequest $request, Critique $critique)
    {
        $opinions = $critique->opinions()->orderBy('created_at', 'desc')->with('critique', 'topics')->get();

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

        return new OpinionResource($opinion->load('critique', 'topics'));
    }
}
