<section>
    <div class="mb-6">
        <p class="text-xs uppercase tracking-[0.22em] text-emerald-200">Account</p>
        <h2 class="mt-1 text-xl font-semibold text-white">Profile Settings</h2>
        <p class="mt-1 text-sm text-slate-300">Update your basic account information.</p>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-2xl border border-emerald-300/35 bg-emerald-300/10 px-4 py-3 text-sm text-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="saveProfile" class="space-y-4">
        <div>
            <label for="name" class="mb-1 block text-sm font-medium text-slate-200">Name</label>
            <input
                wire:model.blur="name"
                id="name"
                type="text"
                autocomplete="name"
                class="h-12 w-full rounded-2xl border border-white/20 bg-slate-950/50 px-4 text-base text-slate-100 placeholder:text-slate-400 focus:border-emerald-300 focus:outline-none"
            >
            @error('name') <p class="mt-1 text-sm text-rose-300">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="mb-1 block text-sm font-medium text-slate-200">Email</label>
            <input
                wire:model.blur="email"
                id="email"
                type="email"
                autocomplete="email"
                class="h-12 w-full rounded-2xl border border-white/20 bg-slate-950/50 px-4 text-base text-slate-100 placeholder:text-slate-400 focus:border-emerald-300 focus:outline-none"
            >
            @error('email') <p class="mt-1 text-sm text-rose-300">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="mb-1 block text-sm font-medium text-slate-200">New Password (optional)</label>
            <input
                wire:model.blur="password"
                id="password"
                type="password"
                autocomplete="new-password"
                placeholder="Minimum 8 characters"
                class="h-12 w-full rounded-2xl border border-white/20 bg-slate-950/50 px-4 text-base text-slate-100 placeholder:text-slate-400 focus:border-emerald-300 focus:outline-none"
            >
            @error('password') <p class="mt-1 text-sm text-rose-300">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="mb-1 block text-sm font-medium text-slate-200">Confirm Password</label>
            <input
                wire:model.blur="password_confirmation"
                id="password_confirmation"
                type="password"
                autocomplete="new-password"
                class="h-12 w-full rounded-2xl border border-white/20 bg-slate-950/50 px-4 text-base text-slate-100 placeholder:text-slate-400 focus:border-emerald-300 focus:outline-none"
            >
        </div>

        <button type="submit" class="flex h-12 w-full items-center justify-center rounded-2xl bg-emerald-300 font-semibold text-slate-950 transition active:scale-[0.99]">
            <span wire:loading.remove wire:target="saveProfile">Save changes</span>
            <span wire:loading wire:target="saveProfile">Saving...</span>
        </button>
    </form>
</section>
