<?php

namespace App\Jobs;

use App\Events\AiResponseReady;
use App\Models\PracticeSession;
use App\Models\Transcript;
use App\Services\ConversationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateAiReplyJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $sessionId,
        public int $userMessageId,
        public int $aiMessageId,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(ConversationService $conversationService): void
    {
        $session = PracticeSession::query()->find($this->sessionId);
        $userMessage = Transcript::query()->find($this->userMessageId);
        $aiMessage = Transcript::query()->find($this->aiMessageId);

        if (!$session || !$userMessage || !$aiMessage) {
            return;
        }

        if ($aiMessage->status !== 'pending') {
            return;
        }

        $reply = $conversationService->generateReply($session, (string) $userMessage->text);

        $aiMessage->update([
            'status' => 'completed',
            'text' => $reply,
            'word_count' => str_word_count($reply),
        ]);

        $session->recalculateMetrics();

        broadcast(new AiResponseReady($session->fresh(), $aiMessage->fresh()));
    }
}
