<?php

namespace App\Events;

use App\Models\PracticeSession;
use App\Models\Transcript;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AiResponseReady implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public PracticeSession $session,
        public Transcript $aiMessage,
    ) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('private-session.'.$this->session->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'AIResponseReady';
    }

    public function broadcastWith(): array
    {
        return [
            'session_id' => $this->session->id,
            'ai_message' => [
                'id' => $this->aiMessage->id,
                'status' => $this->aiMessage->status,
                'text' => (string) $this->aiMessage->text,
                'created_at' => optional($this->aiMessage->created_at)?->toISOString(),
            ],
        ];
    }
}
