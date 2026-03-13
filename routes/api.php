<?php

use App\Http\Controllers\Api\V1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public lookup endpoints
Route::prefix('v1/lookup')->middleware('throttle:30,1')->group(function () {
    Route::post('/dmarc', [V1\LookupController::class, 'dmarc']);
    Route::post('/spf', [V1\LookupController::class, 'spf']);
    Route::post('/dkim', [V1\LookupController::class, 'dkim']);
});

// Authenticated API
Route::prefix('v1')
    ->middleware(['auth:sanctum', 'ensure-team-scope', 'ensure-api-access'])
    ->group(function () {
        Route::apiResource('domains', V1\DomainController::class)->names('api.domains');
        Route::post('domains/{domain}/check-dns', [V1\DomainController::class, 'checkDns']);
        Route::get('domains/{domain}/reports', [V1\ReportController::class, 'domainReports']);

        Route::apiResource('reports', V1\ReportController::class)->only(['index', 'show'])->names('api.reports');

        Route::apiResource('alert-channels', V1\AlertChannelController::class)
            ->parameters(['alert-channels' => 'channel'])->names('api.alert-channels');
        Route::apiResource('alert-rules', V1\AlertRuleController::class)
            ->parameters(['alert-rules' => 'rule'])->names('api.alert-rules');
    });
