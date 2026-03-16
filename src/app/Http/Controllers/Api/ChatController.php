<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateAiReplyJob;
use App\Models\PracticeSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function message(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'session_id' => ['required', 'integer', 'exists:practice_sessions,id'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $session = PracticeSession::query()
            ->whereKey($validated['session_id'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        abort_if($session->status === 'ended', 422, 'Session is already ended.');

        $userMessage = $session->transcripts()->create([
            'speaker' => 'user',
            'status' => 'completed',
            'text' => $validated['message'],
            'word_count' => str_word_count($validated['message']),
        ]);

        $aiMessage = $session->transcripts()->create([
            'speaker' => 'ai',
            'status' => 'pending',
            'text' => null,
            'word_count' => 0,
        ]);

        $session->recalculateMetrics();

        GenerateAiReplyJob::dispatch($session->id, $userMessage->id, $aiMessage->id);

        return response()->json([
            'data' => [
                'user_message' => [
                    'id' => $userMessage->id,
                    'session_id' => $session->id,
                    'speaker' => 'user',
                    'text' => $userMessage->text,
                ],
                'ai_message' => [
                    'id' => $aiMessage->id,
                    'status' => 'pending',
                ],
            ],
            'meta' => [
                'delivery' => 'websocket',
                'event' => 'AIResponseReady',
                'channel' => 'private-session.'.$session->id,
            ],
        ], 202);
    }
}
