# Backend Architecture (Laravel 12, MVP)

## Folder Structure (Target)

- `app/Services/AI/ConversationService.php`
- `app/Services/AI/PromptBuilder.php`
- `app/Services/Progress/ConfidenceEngine.php`
- `app/Services/Progress/LevelAssessmentService.php`
- `app/Jobs/GenerateAIResponseJob.php`
- `app/Events/AIResponseReady.php`
- `app/Models/Session.php`
- `app/Models/Transcript.php`
- `app/Models/ProgressSnapshot.php`

## Key Services

### ConversationService
Responsibilities:
- Load user level and lesson prompt
- Build model input
- Call LLM provider
- Return bounded output

### ConfidenceEngine
Responsibilities:
- Compute MVP confidence score from text behavior
- Update session and snapshot scores

### LevelAssessmentService
Responsibilities:
- Assign baseline user level
- Periodic reassessment (weekly)

## Message Processing Flow (Hybrid)

1. API receives user message
2. Persist user transcript
3. Create pending AI transcript record
4. Dispatch `GenerateAIResponseJob`
5. Return `202` with pending message id
6. Job completes AI message
7. Broadcast `AIResponseReady` via Reverb

## Persistence

- Database: MySQL
- Cache/queue broker: Redis

MVP DB approach:
- Minimal constraints
- Essential indexes only
- Tighten rules after usage data
