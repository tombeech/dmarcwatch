<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReportResource;
use App\Models\DmarcReport;
use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ReportController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $team = $request->user()->currentTeam;

        $reports = DmarcReport::with('domain')
            ->where('team_id', $team->id)
            ->when($request->domain_id, fn ($q, $id) => $q->where('domain_id', $id))
            ->latest('received_at')
            ->paginate(25);

        return ReportResource::collection($reports);
    }

    public function show(DmarcReport $report): ReportResource
    {
        return new ReportResource($report->load('records.sendingSource'));
    }

    public function domainReports(Domain $domain): AnonymousResourceCollection
    {
        $reports = $domain->dmarcReports()->latest('received_at')->paginate(25);
        return ReportResource::collection($reports);
    }
}
