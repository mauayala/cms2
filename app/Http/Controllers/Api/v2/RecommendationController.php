<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Recommendation;

class RecommendationController extends Controller
{
    public function index(Request $request)
    {
        $recommendations = Recommendation::with(['video', 'serie'])->orderBy('order', 'ASC')->get();

        return response()->json(['recommendations' => $recommendations]);
    }
}
