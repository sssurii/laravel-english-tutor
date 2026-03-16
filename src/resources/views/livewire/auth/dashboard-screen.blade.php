<section>
    <div class="rounded-2xl border border-emerald-200/30 bg-emerald-300/10 p-4">
        <p class="text-xs font-medium uppercase tracking-[0.2em] text-emerald-200">Signed In</p>
        <h1 class="mt-2 text-2xl font-semibold text-white">Hi, {{ auth()->user()->name }}.</h1>
        <p class="mt-2 text-sm text-slate-200">Your mobile auth UI is working in Jump.</p>
    </div>

    <div class="mt-4 rounded-2xl border border-white/15 bg-slate-950/60 p-4 text-sm text-slate-200">
        <p><span class="text-slate-400">Email:</span> {{ auth()->user()->email }}</p>
        <p class="mt-2"><span class="text-slate-400">Status:</span> Ready for next mobile screens.</p>
    </div>

    <button
        wire:click="logout"
        class="mt-6 flex h-12 w-full items-center justify-center rounded-2xl border border-rose-300/40 bg-rose-400/10 font-semibold text-rose-200 transition active:scale-[0.99]"
    >
        <span wire:loading.remove wire:target="logout">Sign out</span>
        <span wire:loading wire:target="logout">Signing out...</span>
    </button>
</section>
