# AI Spoken English Tutor (Confidence-First) - MVP Development Plan

## 1. Product Vision

Build an Android-first mobile app that helps Indian working adults gain confidence speaking English through AI conversation practice.

MVP scope is text-first conversation. Voice support is planned later.

Primary goals:
- Build speaking confidence first
- Improve fluency gradually
- Track progress in simple measurable ways

Target users:
- Job seekers
- Workers with limited spoken English
- Adults with low speaking confidence

## 2. Product Principles

1. Confidence first, grammar second
2. Short, practical conversations
3. Fast feedback loop
4. MVP over perfection
5. Keep costs controlled

## 3. Core Architecture

NativePHP Mobile (Android) -> Laravel 12 API -> Conversation Service -> LLM Provider -> Progress Engine -> MySQL

Voice-ready future layer:
- Voice Input -> STT -> Conversation Engine -> TTS -> Audio Output

## 4. Tech Stack (Latest)

Frontend:
- NativePHP Mobile (Android first)

Backend:
- PHP 8.4+
- Laravel 12
- Laravel Sanctum
- Laravel Reverb (WebSockets)
- Laravel Queues
- Redis
- MySQL 8

AI:
- LLM API with short conversational output

Future voice:
- Native Android SpeechRecognizer
- iOS Speech Framework (future phase)

## 5. Core Features

Phase 1 (MVP):
- User auth
- Chat-based practice
- Beginner lesson prompts
- AI conversation engine
- Basic progress tracking
- Basic confidence scoring
- Hybrid response delivery (instant placeholder + async final response)

Phase 2:
- Voice input (STT)
- AI speech output (TTS)
- Pronunciation analysis

Phase 3:
- Interview simulator
- Advanced grammar coaching
- Premium features

## 6. Database Schema (MVP, Loose Constraints)

Constraint strategy for MVP:
- Keep schema flexible
- Use essential indexes only
- Add stricter constraints after MVP validation

### users
- Laravel default users table

### user_profiles
- id
- user_id
- current_level
- confidence_score
- fluency_score
- grammar_score
- created_at
- updated_at

### sessions
Text-first columns for MVP:
- id
- user_id
- message_count
- user_message_count
- ai_message_count
- avg_user_message_length
- confidence_score
- started_at
- ended_at
- created_at
- updated_at

### transcripts
- id
- session_id
- speaker (user/ai/system)
- text
- word_count
- grammar_errors (nullable, MVP)
- created_at

### progress_snapshots
- id
- user_id
- confidence_score
- fluency_score
- grammar_score
- vocabulary_score
- snapshot_date
- created_at

### lessons
- id
- level
- topic
- system_prompt
- created_at
- updated_at

MVP indexes:
- sessions(user_id, started_at)
- transcripts(session_id, created_at)
- progress_snapshots(user_id, snapshot_date)
- lessons(level, topic)

## 7. Core Backend Services

ConversationService:
- Load lesson prompt by level/topic
- Build prompt payload
- Call AI provider
- Enforce token/output limits
- Return response + metadata

ConfidenceEngine (MVP v1):
- Compute simple confidence metric from text behavior
- Inputs: message length, consistency, session completion
- Persist session and snapshot scores

LevelAssessmentService:
- Initial level assignment
- Weekly level reassessment

## 8. AI Prompt Strategy (Simple First)

System prompt baseline:
- Friendly spoken English tutor for Indian job adults
- Keep responses short (1-3 sentences)
- Ask simple follow-up questions
- Encourage before correcting

Correction approach:
1. Encourage user
2. Provide natural corrected version
3. Ask next simple question

## 9. Confidence Scoring Model (MVP)

Initial scoring formula (temporary):
- Confidence = 0.4 Fluency + 0.3 Vocabulary + 0.3 Engagement

MVP note:
- Keep scoring lightweight now
- Revisit after real user data and calibration

## 10. Real-Time Messaging (Hybrid)

Hybrid delivery flow:
1. User sends message via API
2. API saves transcript and returns immediate ack + placeholder state
3. Queue job generates AI response
4. Response broadcast via Reverb event
5. Mobile client replaces placeholder with final response

Core event:
- AIResponseReady

Channel pattern:
- private-session.{sessionId}

## 11. Infrastructure (MVP)

Initial deployment target:
- 1 VPS (8 GB RAM, 4 vCPU)

Services:
- Laravel API
- MySQL
- Redis
- Reverb server
- Queue workers

Estimated early cost:
- ~USD 50-70/month depending AI usage and VPS region

## 12. Development Roadmap (MVP)

### Step 1: Project setup
- Finalize Laravel 12 + NativePHP Android setup
- Configure environment and service wiring

### Step 2: Auth
- Register/login/logout APIs
- Sanctum token flow
- Android login UI

### Step 3: Schema
- Create MVP migrations (text-first session metrics)
- Seed beginner lesson prompts

### Step 4: Conversation engine
- Implement ConversationService
- Add AI provider wrapper
- Implement prompt templates and token guards

### Step 5: Chat API (hybrid)
- POST message endpoint with immediate ack
- Queue AI generation job
- Persist transcripts

### Step 6: Mobile chat UI
- Chat timeline UI
- Pending/typing placeholder state
- Final response reconciliation from WebSocket event

### Step 7: Confidence engine
- Implement v1 scoring
- Write session/progress updates

### Step 8: Reverb integration
- Broadcast AIResponseReady
- Subscribe by session channel

### Step 9: Progress tracking
- Summary endpoint
- Snapshot generation job

### Step 10: Lesson system
- Topic rotation
- Daily lesson selection

## 13. Parallel Research Track

While MVP builds:
- Explore NativePHP plugin for Android speech recognition
- Measure latency and transcript quality

## 14. Testing Strategy (MVP)

- Unit tests: ConversationService, ConfidenceEngine
- Feature tests: auth, chat message, session lifecycle, progress summary
- Manual UX tests: Android chat state transitions (pending -> final)

## 15. MVP Success Metrics

- Daily active users
- Avg session duration
- Messages per session
- Session completion rate
- Confidence trend over time
- 7-day retention

## 16. Future Enhancements

- Voice conversation
- Pronunciation scoring
- Interview roleplay
- Streaks and gamification

## 17. Cost Estimate (Early Stage)

At 100 active users (early assumptions):
- Infrastructure: USD 50-60
- AI usage: USD 10-20
- Total: ~USD 60-80/month

## 18. Timeline (MVP)

- Week 1-2: setup, auth, schema
- Week 3: conversation engine + chat API
- Week 4: Android chat UI + hybrid realtime
- Week 5: scoring + progress
- Week 6: testing + closed beta
