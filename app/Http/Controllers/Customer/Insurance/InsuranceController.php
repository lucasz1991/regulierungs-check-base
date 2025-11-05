<?php

namespace App\Http\Controllers\Customer\Insurance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Insurance;
use App\Jobs\Insurance\AnalyzeInsuranceClaimRatings;

class InsuranceController extends Controller
{
    public static function analyzeClaimRatings(Insurance $insurance)
    {
        AnalyzeInsuranceClaimRatings::dispatch($insurance);

        return response()->json(['success' => true]);
    }
}
