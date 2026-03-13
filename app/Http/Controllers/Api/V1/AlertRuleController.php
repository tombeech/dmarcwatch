<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AlertRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlertRuleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $team = $request->user()->currentTeam;
        $rules = AlertRule::with(['alertChannel', 'domain'])->where('team_id', $team->id)->get();
        return response()->json(['data' => $rules]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'alert_channel_id' => 'required|exists:alert_channels,id',
            'domain_id' => 'nullable|exists:domains,id',
            'event_types' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $team = $request->user()->currentTeam;
        $validated['team_id'] = $team->id;
        $validated['is_active'] = $validated['is_active'] ?? true;

        $rule = AlertRule::create($validated);
        return response()->json(['data' => $rule->load(['alertChannel', 'domain'])], 201);
    }

    public function show(AlertRule $rule): JsonResponse
    {
        return response()->json(['data' => $rule->load(['alertChannel', 'domain'])]);
    }

    public function update(Request $request, AlertRule $rule): JsonResponse
    {
        $validated = $request->validate([
            'alert_channel_id' => 'sometimes|exists:alert_channels,id',
            'domain_id' => 'nullable|exists:domains,id',
            'event_types' => 'nullable|array',
            'is_active' => 'sometimes|boolean',
        ]);

        $rule->update($validated);
        return response()->json(['data' => $rule->fresh()->load(['alertChannel', 'domain'])]);
    }

    public function destroy(AlertRule $rule): JsonResponse
    {
        $rule->delete();
        return response()->json(null, 204);
    }
}
