<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Models\Critique;
use App\Models\Opinion;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function __invoke(SearchRequest $request)
    {
        $search = $request->input('search') ?? null;

        $critiques = [];
        $topics = [];
        $opinions = [];

        if ($search) {
            $authenticatedUser = Auth::user();
            $authenticatedCritique = $authenticatedUser->critique;
            $authenticatedCritiqueId = $authenticatedCritique->id;

            $critiques = Critique::where('id', '<>', $authenticatedCritiqueId)
                ->where('name', 'LIKE', "%$search%")
                ->orWhere('username', 'LIKE', "%$search%")
                ->paginate(10);

            $topics = Topic::where('text', 'LIKE', "%$search%")
                ->paginate(10);

            $opinions = Opinion::where('text', 'LIKE', "%$search%")
                ->paginate(10);
        }

        return response()->json([
            'critiques' => $critiques,
            'topics' => $topics,
            'opinions' => $opinions,
        ]);
    }
}
