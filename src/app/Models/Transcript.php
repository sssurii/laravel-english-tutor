<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Transcript extends Model
{
    protected $fillable = [
        'practice_session_id',
        'speaker',
        'status',
        'text',
        'word_count',
        'grammar_errors',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(PracticeSession::class, 'practice_session_id');
    }
}
