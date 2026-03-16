<?php

namespace App\Services;

use App\Ai\Agents\TutorChatAgent;
use App\Models\PracticeSession;
use Throwable;

class ConversationService
{
    public function generateReply(PracticeSession $session, string $userMessage): string
    {
        $message = trim($userMessage);
        if ($message === '') {
            return 'Can you share one sentence so we can continue?';
        }

        if (! $this->hasConfiguredDefaultProviderKey()) {
            return $this->fallbackReply($message);
        }

        $history = $session->transcripts()
            ->whereNotNull('text')
            ->orderBy('id')
            ->get(['speaker', 'text'])
            ->map(fn ($transcript) => [
                'speaker' => (string) $transcript->speaker,
                'text' => (string) $transcript->text,
            ])
            ->all();

        try {
            $response = TutorChatAgent::make(history: $history)->prompt(
                prompt:$message,
                model: 'gemini-2.0',
            );
            $text = trim((string) $response->text);

            return $text !== '' ? $text : $this->fallbackReply($message);
        } catch (Throwable) {
            return $this->fallbackReply($message);
        }
    }

    private function hasConfiguredDefaultProviderKey(): bool
    {
        $provider = (string) config('ai.default', 'openai');
        $key = config("ai.providers.{$provider}.key");

        return is_string($key) && trim($key) !== '';
    }

    private function fallbackReply(string $input): string
    {
        $normalized = strtolower($input);

        if (str_contains($normalized, 'my name is')) {
            return 'Nice to meet you. Try: "My name is ... and I am improving my English." What do you do for work?';
        }

        return 'Good effort. Try adding one more sentence with a specific detail. What happened today?';
    }
}
