<?php

namespace App\Http\Controllers;

use App\Http\Requests\Opinion\OpinionDestroyRequest;
use App\Http\Requests\Opinion\OpinionShowRequest;
use App\Http\Requests\Opinion\OpinionUpdateRequest;
use App\Http\Resources\OpinionResource;
use App\Models\Opinion;
use Illuminate\Http\Request;

class OpinionController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Opinion  $opinion
     * @return \Illuminate\Http\Response
     */
    public function show(OpinionShowRequest $request, Opinion $opinion)
    {
        return new OpinionResource($opinion);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Opinion  $opinion
     * @return \Illuminate\Http\Response
     */
    public function update(OpinionUpdateRequest $request, Opinion $opinion)
    {
        $opinion->update($request->validated());

        return new OpinionResource($opinion);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Opinion  $opinion
     * @return \Illuminate\Http\Response
     */
    public function destroy(OpinionDestroyRequest $request, Opinion $opinion)
    {
        $opinion->delete();

        return null;
    }
}
