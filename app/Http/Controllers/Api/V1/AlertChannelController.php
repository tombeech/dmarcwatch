<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\AlertChannelType;
use App\Http\Controllers\Controller;
use App\Models\AlertChannel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlertChannelController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $team = $request->user()->currentTeam;
        $channels = AlertChannel::where('team_id', $team->id)->orderBy('name')->get();
        return response()->json(['data' => $channels]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:' . implode(',', array_column(AlertChannelType::cases(), 'value')),
            'config' => 'required|array',
        ]);

        $team = $request->user()->currentTeam;

        $channel = AlertChannel::create([
            'team_id' => $team->id,
            'name' => $validated['name'],
            'type' => $validated['type'],
            'config' => $validated['config'],
            'is_active' => true,
            'is_verified' => false,
        ]);

        return response()->json(['data' => $channel], 201);
    }

    public function show(AlertChannel $channel): JsonResponse
    {
        return response()->json(['data' => $channel]);
    }

    public function update(Request $request, AlertChannel $channel): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'config' => 'sometimes|array',
            'is_active' => 'sometimes|boolean',
        ]);

        $channel->update($validated);
        return response()->json(['data' => $channel->fresh()]);
    }

    public function destroy(AlertChannel $channel): JsonResponse
    {
        $channel->delete();
        return response()->json(null, 204);
    }
}
