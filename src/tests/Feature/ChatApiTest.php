<?php

use App\Ai\Agents\TutorChatAgent;
use App\Jobs\GenerateAiReplyJob;
use App\Models\PracticeSession;
use App\Models\Transcript;
use App\Models\User;
use App\Services\ConversationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

it('starts and ends practice sessions through api', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('api-test')->plainTextToken;

    $start = $this
        ->withHeader('Authorization', 'Bearer '.$token)
        ->postJson('/api/v1/sessions/start');

    $start->assertCreated()
        ->assertJsonPath('data.session.status', 'active');

    $sessionId = (int) $start->json('data.session.id');

    $this->withHeader('Authorization', 'Bearer '.$token)
        ->postJson("/api/v1/sessions/{$sessionId}/end")
        ->assertOk()
        ->assertJsonPath('data.session.status', 'ended');
});

it('accepts chat message and queues ai response generation', function (): void {
    Queue::fake();

    $user = User::factory()->create();
    $token = $user->createToken('api-test')->plainTextToken;
    $session = PracticeSession::query()->create([
        'user_id' => $user->id,
        'status' => 'active',
        'started_at' => now(),
    ]);

    $response = $this
        ->withHeader('Authorization', 'Bearer '.$token)
        ->postJson('/api/v1/chat/message', [
            'session_id' => $session->id,
            'message' => 'Hello, I want to practice.',
        ]);

    $response->assertStatus(202)
        ->assertJsonPath('meta.event', 'AIResponseReady')
        ->assertJsonPath('meta.channel', 'private-session.'.$session->id)
        ->assertJsonPath('data.user_message.speaker', 'user')
        ->assertJsonPath('data.ai_message.status', 'pending');

    expect($session->transcripts()->count())->toBe(2);
    Queue::assertPushed(GenerateAiReplyJob::class);
});

it('finalizes pending ai transcript when job runs', function (): void {
    config()->set('ai.providers.openai.key', 'test-key');
    TutorChatAgent::fake(['AI reply from fake']);

    $user = User::factory()->create();
    $session = PracticeSession::query()->create([
        'user_id' => $user->id,
        'status' => 'active',
        'started_at' => now(),
    ]);

    $userMessage = Transcript::query()->create([
        'practice_session_id' => $session->id,
        'speaker' => 'user',
        'status' => 'completed',
        'text' => 'Can you help me practice?',
        'word_count' => 5,
    ]);

    $aiMessage = Transcript::query()->create([
        'practice_session_id' => $session->id,
        'speaker' => 'ai',
        'status' => 'pending',
        'text' => null,
        'word_count' => 0,
    ]);

    $job = new GenerateAiReplyJob($session->id, $userMessage->id, $aiMessage->id);
    $job->handle(app(ConversationService::class));

    $aiMessage->refresh();

    expect($aiMessage->status)->toBe('completed');
    expect($aiMessage->text)->toContain('AI reply from fake');
});
