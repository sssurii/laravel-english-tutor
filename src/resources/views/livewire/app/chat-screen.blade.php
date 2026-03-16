<section>
    <div class="mb-4 flex items-center justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.22em] text-cyan-200">Practice</p>
            <h2 class="mt-1 text-xl font-semibold text-white">Text Chat</h2>
            <p class="mt-1 text-xs text-slate-400">Session #{{ $sessionId }}</p>
        </div>
        <button
            wire:click="clearChat"
            class="rounded-xl border border-white/20 px-3 py-2 text-xs font-semibold text-slate-200"
        >
            Clear
        </button>
    </div>

    <div wire:poll.4s="syncMessages" class="mb-4 max-h-[22rem] space-y-3 overflow-y-auto rounded-2xl border border-white/10 bg-slate-950/60 p-3">
        @if (count($messages) === 0)
            <p class="rounded-2xl bg-slate-800/80 px-3 py-2 text-sm text-slate-200">
                Hi! Send your first message to start practicing.
            </p>
        @endif
        @foreach ($messages as $entry)
            @php($isUser = ($entry['sender'] ?? '') === 'user')
            <div class="flex {{ $isUser ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[80%] rounded-2xl px-3 py-2 text-sm {{ $isUser ? 'bg-cyan-300 text-slate-950' : 'bg-slate-800 text-slate-100' }}">
                    {{ $entry['text'] }}
                    @if (($entry['pending'] ?? false) === true)
                        <span class="ml-1 inline-block animate-pulse text-xs text-slate-300">...</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <form wire:submit="sendMessage" class="space-y-3">
        <label for="chat-message" class="sr-only">Message</label>
        <textarea
            wire:model.blur="message"
            id="chat-message"
            rows="3"
            maxlength="500"
            class="w-full rounded-2xl border border-white/20 bg-slate-950/50 px-4 py-3 text-base text-slate-100 placeholder:text-slate-400 focus:border-cyan-300 focus:outline-none"
            placeholder="Type your message..."
        ></textarea>
        @error('message') <p class="text-sm text-rose-300">{{ $message }}</p> @enderror

        <button type="submit" class="flex h-12 w-full items-center justify-center rounded-2xl bg-cyan-300 font-semibold text-slate-950 transition active:scale-[0.99]">
            <span wire:loading.remove wire:target="sendMessage">Send message</span>
            <span wire:loading wire:target="sendMessage">Sending...</span>
        </button>
    </form>
</section>
