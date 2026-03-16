<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class PracticeSession extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'message_count',
        'user_message_count',
        'ai_message_count',
        'avg_user_message_length',
        'confidence_score',
        'started_at',
        'ended_at',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'avg_user_message_length' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transcripts(): HasMany
    {
        return $this->hasMany(Transcript::class);
    }

    public function recalculateMetrics(): void
    {
        $userMessages = $this->transcripts()->where('speaker', 'user')->get(['text']);
        $aiCount = $this->transcripts()->where('speaker', 'ai')->count();
        $userCount = $userMessages->count();

        $avgLength = $userCount > 0
            ? round($userMessages->avg(fn ($message) => str_word_count((string) $message->text)) ?? 0, 2)
            : 0;

        $this->forceFill([
            'user_message_count' => $userCount,
            'ai_message_count' => $aiCount,
            'message_count' => $userCount + $aiCount,
            'avg_user_message_length' => $avgLength,
        ])->save();
    }
}
