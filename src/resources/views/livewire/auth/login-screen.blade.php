<section>
    <div class="mb-6">
        <p class="text-xs font-medium uppercase tracking-[0.24em] text-cyan-200">Welcome Back</p>
        <h1 class="mt-2 text-2xl font-semibold text-white">Sign in</h1>
        <p class="mt-2 text-sm text-slate-300">Use your account to continue in the mobile app preview.</p>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-2xl border border-emerald-300/35 bg-emerald-300/10 px-4 py-3 text-sm text-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="login" class="space-y-4">
        <div>
            <label for="email" class="mb-1 block text-sm font-medium text-slate-200">Email</label>
            <input
                wire:model.blur="email"
                id="email"
                type="email"
                autocomplete="email"
                class="h-12 w-full rounded-2xl border border-white/20 bg-slate-950/50 px-4 text-base text-slate-100 placeholder:text-slate-400 focus:border-cyan-300 focus:outline-none"
                placeholder="you@example.com"
            >
            @error('email') <p class="mt-1 text-sm text-rose-300">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="mb-1 block text-sm font-medium text-slate-200">Password</label>
            <input
                wire:model.blur="password"
                id="password"
                type="password"
                autocomplete="current-password"
                class="h-12 w-full rounded-2xl border border-white/20 bg-slate-950/50 px-4 text-base text-slate-100 placeholder:text-slate-400 focus:border-cyan-300 focus:outline-none"
                placeholder="Minimum 8 characters"
            >
            @error('password') <p class="mt-1 text-sm text-rose-300">{{ $message }}</p> @enderror
        </div>

        <label class="flex items-center gap-3 text-sm text-slate-300">
            <input wire:model="remember" type="checkbox" class="h-4 w-4 rounded border-white/40 bg-slate-900 text-cyan-400 focus:ring-cyan-300/60">
            Keep me signed in
        </label>

        <div class="text-right">
            <a href="{{ route('password.request') }}" wire:navigate class="text-sm font-medium text-cyan-200">Forgot password?</a>
        </div>

        <button type="submit" class="mt-2 flex h-12 w-full items-center justify-center rounded-2xl bg-cyan-300 font-semibold text-slate-950 transition active:scale-[0.99]">
            <span wire:loading.remove wire:target="login">Sign in</span>
            <span wire:loading wire:target="login">Signing in...</span>
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-300">
        New here?
        <a href="{{ route('register') }}" wire:navigate class="font-semibold text-cyan-200">Create account</a>
    </p>
</section>
