<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\DkimVerifier;
use App\Services\DmarcAnalyzer;
use App\Services\SpfAnalyzer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LookupController extends Controller
{
    public function dmarc(Request $request, DmarcAnalyzer $analyzer): JsonResponse
    {
        $request->validate(['domain' => 'required|string|max:253']);
        return response()->json($analyzer->analyze($request->domain));
    }

    public function spf(Request $request, SpfAnalyzer $analyzer): JsonResponse
    {
        $request->validate(['domain' => 'required|string|max:253']);
        return response()->json($analyzer->analyze($request->domain));
    }

    public function dkim(Request $request, DkimVerifier $verifier): JsonResponse
    {
        $request->validate([
            'domain' => 'required|string|max:253',
            'selector' => 'required|string|max:100',
        ]);
        return response()->json($verifier->verify($request->domain, $request->selector));
    }
}
