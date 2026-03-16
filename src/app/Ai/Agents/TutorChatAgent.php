<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Messages\AssistantMessage;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Messages\UserMessage;
use Laravel\Ai\Promptable;
use Stringable;

class TutorChatAgent implements Agent, Conversational
{
    use Promptable;

    /**
     * @param list<array{speaker: string, text: string}> $history
     */
    public function __construct(private array $history = [])
    {
    }

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return <<<'PROMPT'
You are an English speaking tutor for Indian working adults.
- Keep responses short: 1-3 sentences.
- Be friendly and confidence-first.
- Correct gently by suggesting a natural sentence.
- Ask one simple follow-up question.
PROMPT;
    }

    /**
     * Get the list of messages comprising the conversation so far.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        return collect($this->history)
            ->take(-12)
            ->map(function (array $entry): Message {
                return match ($entry['speaker']) {
                    'user' => new UserMessage($entry['text']),
                    default => new AssistantMessage($entry['text']),
                };
            })
            ->all();
    }
}
