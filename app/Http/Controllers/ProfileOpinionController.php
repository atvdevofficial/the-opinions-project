<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileOpinion\ProfileOpinionIndexRequest;
use App\Http\Requests\ProfileOpinion\ProfileOpinionStoreRequest;
use App\Http\Resources\OpinionResource;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileOpinionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProfileOpinionIndexRequest $request, Profile $profile)
    {
        $opinions = $profile->opinions;

        return OpinionResource::collection($opinions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProfileOpinionStoreRequest $request, Profile $profile)
    {
        $opinion = $profile->opinions()->create($request->validated());

        return new OpinionResource($opinion);
    }
}
