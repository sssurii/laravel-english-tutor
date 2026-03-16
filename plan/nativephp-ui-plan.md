# NativePHP Mobile UI Plan (Android MVP)

## MVP Screens

1. Login
2. Onboarding (basic level + goal)
3. Chat
4. Progress Summary

## Chat Screen Layout

Header:
- Session timer
- Exit button

Message list:
- User bubbles
- AI bubbles
- Pending AI bubble state (`...` / "Thinking...")

Input area:
- Text input
- Send button
- Microphone button placeholder (disabled in MVP)

## Hybrid Response UX

After user sends message:
1. Immediately append user message
2. Show pending AI placeholder
3. Replace placeholder when `AIResponseReady` event arrives
4. If timeout/failure, show retry state

## UX Guidelines

- Large readable font
- Minimal controls
- Friendly non-judgmental tone
- Short AI responses (1-3 sentences)
- Single primary action per screen
