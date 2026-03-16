<?php

namespace App\Livewire\App;

use App\Jobs\GenerateAiReplyJob;
use App\Models\PracticeSession;
use Livewire\Component;

class ChatScreen extends Component
{
    public int $sessionId;
    public string $message = '';

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $messages = [];

    public function mount(): void
    {
        $session = PracticeSession::query()
            ->where('user_id', auth()->id())
            ->where('status', 'active')
            ->latest('id')
            ->first();

        if (! $session) {
            $session = PracticeSession::query()->create([
                'user_id' => auth()->id(),
                'status' => 'active',
                'started_at' => now(),
            ]);
        }

        $this->sessionId = $session->id;
        $this->syncMessages();
    }

    public function getListeners(): array
    {
        return [
            "echo-private:private-session.{$this->sessionId},AIResponseReady" => 'handleAiResponseReady',
        ];
    }

    public function sendMessage(): void
    {
        $validated = $this->validate([
            'message' => ['required', 'string', 'max:500'],
        ]);

        $input = trim($validated['message']);

        $session = PracticeSession::query()->findOrFail($this->sessionId);
        abort_if((int) $session->user_id !== (int) auth()->id(), 403);

        $userMessage = $session->transcripts()->create([
            'speaker' => 'user',
            'status' => 'completed',
            'text' => $input,
            'word_count' => str_word_count($input),
        ]);

        $aiMessage = $session->transcripts()->create([
            'speaker' => 'ai',
            'status' => 'pending',
            'text' => null,
            'word_count' => 0,
        ]);

        $this->message = '';

        $session->recalculateMetrics();
        GenerateAiReplyJob::dispatch($session->id, $userMessage->id, $aiMessage->id);

        $this->syncMessages();
    }

    public function clearChat(): void
    {
        $session = PracticeSession::query()->findOrFail($this->sessionId);
        abort_if((int) $session->user_id !== (int) auth()->id(), 403);

        $session->transcripts()->delete();
        $session->recalculateMetrics();
        $this->syncMessages();
    }

    public function syncMessages(): void
    {
        $session = PracticeSession::query()->findOrFail($this->sessionId);
        abort_if((int) $session->user_id !== (int) auth()->id(), 403);

        $this->messages = $session->transcripts()
            ->orderBy('id')
            ->get()
            ->map(fn ($message) => [
                'id' => $message->id,
                'sender' => $message->speaker === 'ai' ? 'assistant' : $message->speaker,
                'text' => $message->status === 'pending'
                    ? 'Thinking'
                    : (string) $message->text,
                'pending' => $message->status === 'pending',
            ])
            ->all();
    }

    public function handleAiResponseReady(): void
    {
        $this->syncMessages();
    }

    public function render()
    {
        return view('livewire.app.chat-screen')
            ->layout('layouts.mobile-app');
    }
}
