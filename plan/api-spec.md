# API Specification (MVP, Evolving)

Base URL: `/api/v1`

Auth: Laravel Sanctum bearer tokens
- Header: `Authorization: Bearer <token>`

Format:
- Request: JSON
- Response: JSON

## Conventions

Success envelope (example):
```json
{
  "data": {},
  "meta": {}
}
```

Error envelope:
```json
{
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "The given data was invalid.",
    "details": {}
  }
}
```

HTTP status basics:
- `200` OK
- `201` Created
- `202` Accepted (async processing)
- `401` Unauthorized
- `403` Forbidden
- `404` Not Found
- `422` Validation error
- `500` Server error

## Auth

### POST `/register`
Request:
```json
{
  "name": "Raj",
  "email": "raj@example.com",
  "password": "secret1234",
  "password_confirmation": "secret1234"
}
```
Response `201`:
```json
{
  "data": {
    "user": {"id": 1, "name": "Raj", "email": "raj@example.com"},
    "token": "..."
  }
}
```

### POST `/login`
Request:
```json
{
  "email": "raj@example.com",
  "password": "secret1234"
}
```
Response `200`: same as register token shape.

### POST `/logout`
Response `200`:
```json
{"data": {"logged_out": true}}
```

## Sessions

### POST `/sessions/start`
Response `201`:
```json
{
  "data": {
    "session": {
      "id": 101,
      "started_at": "2026-03-09T10:00:00Z",
      "status": "active"
    }
  }
}
```

### POST `/sessions/{session}/end`
Response `200`:
```json
{
  "data": {
    "session": {
      "id": 101,
      "ended_at": "2026-03-09T10:20:00Z",
      "status": "ended"
    }
  }
}
```

### GET `/sessions/history`
Query params:
- `page` (default 1)
- `per_page` (default 20, max 50)

## Chat (Hybrid)

### POST `/chat/message`
Behavior:
- Save user transcript immediately
- Return `202 Accepted` with pending AI message reference
- Final AI text arrives via WebSocket event

Request:
```json
{
  "session_id": 101,
  "message": "Hello, I want to practice English"
}
```

Response `202`:
```json
{
  "data": {
    "user_message": {
      "id": 1001,
      "session_id": 101,
      "speaker": "user",
      "text": "Hello, I want to practice English"
    },
    "ai_message": {
      "id": 1002,
      "status": "pending"
    }
  },
  "meta": {
    "delivery": "websocket",
    "event": "AIResponseReady",
    "channel": "private-session.101"
  }
}
```

### WebSocket Event: `AIResponseReady`
Payload:
```json
{
  "session_id": 101,
  "ai_message": {
    "id": 1002,
    "status": "completed",
    "text": "Great! Tell me about your job.",
    "created_at": "2026-03-09T10:00:05Z"
  }
}
```

## Progress

### GET `/progress/summary`
Response `200`:
```json
{
  "data": {
    "confidence_score": 60,
    "fluency_score": 55,
    "grammar_score": 40,
    "vocabulary_score": 50
  }
}
```

## Notes

- API spec is intentionally MVP-level and will evolve.
- Keep backward compatibility within `/api/v1` during MVP where practical.
