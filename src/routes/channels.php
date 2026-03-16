<?php

use App\Models\PracticeSession;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('private-session.{sessionId}', function ($user, int $sessionId) {
    return PracticeSession::query()
        ->whereKey($sessionId)
        ->where('user_id', $user->id)
        ->exists();
});
