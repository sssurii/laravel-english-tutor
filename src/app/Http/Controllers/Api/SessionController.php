<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PracticeSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function start(Request $request): JsonResponse
    {
        $session = PracticeSession::query()->create([
            'user_id' => $request->user()->id,
            'status' => 'active',
            'started_at' => now(),
        ]);

        return response()->json([
            'data' => [
                'session' => [
                    'id' => $session->id,
                    'started_at' => $session->started_at?->toISOString(),
                    'status' => $session->status,
                ],
            ],
        ], 201);
    }

    public function end(Request $request, PracticeSession $session): JsonResponse
    {
        abort_unless((int) $session->user_id === (int) $request->user()->id, 403);

        if ($session->status !== 'ended') {
            $session->update([
                'status' => 'ended',
                'ended_at' => now(),
            ]);
            $session->recalculateMetrics();
        }

        return response()->json([
            'data' => [
                'session' => [
                    'id' => $session->id,
                    'ended_at' => $session->ended_at?->toISOString(),
                    'status' => $session->status,
                ],
            ],
        ]);
    }

    public function history(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 20), 1), 50);

        $sessions = PracticeSession::query()
            ->where('user_id', $request->user()->id)
            ->latest('started_at')
            ->paginate($perPage);

        return response()->json([
            'data' => $sessions->getCollection()->map(fn (PracticeSession $session) => [
                'id' => $session->id,
                'status' => $session->status,
                'message_count' => $session->message_count,
                'started_at' => $session->started_at?->toISOString(),
                'ended_at' => $session->ended_at?->toISOString(),
            ]),
            'meta' => [
                'current_page' => $sessions->currentPage(),
                'last_page' => $sessions->lastPage(),
                'per_page' => $sessions->perPage(),
                'total' => $sessions->total(),
            ],
        ]);
    }

    public function transcripts(Request $request, PracticeSession $session): JsonResponse
    {
        abort_unless((int) $session->user_id === (int) $request->user()->id, 403);

        $transcripts = $session->transcripts()
            ->orderBy('id')
            ->get()
            ->map(fn ($item) => [
                'id' => $item->id,
                'speaker' => $item->speaker,
                'status' => $item->status,
                'text' => $item->text,
                'created_at' => $item->created_at?->toISOString(),
            ]);

        return response()->json([
            'data' => [
                'session_id' => $session->id,
                'transcripts' => $transcripts,
            ],
        ]);
    }
}
