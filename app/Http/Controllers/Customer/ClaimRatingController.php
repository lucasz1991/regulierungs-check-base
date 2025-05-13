<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Jobs\ClaimRatingAIEval;



class ClaimRatingController extends Controller
{
    static function evaluateScore(ClaimRating $claimRating)
    {
        ClaimRatingAIEval::dispatch($claimRating);
        return response()->json([
            'success' => true,
        ]);
    }



}
