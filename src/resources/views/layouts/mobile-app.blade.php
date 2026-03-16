<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <title>{{ config('app.name', 'English Tutor') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="min-h-screen bg-slate-950 text-slate-100">
        <div class="relative min-h-screen overflow-hidden pb-24">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(14,165,233,0.3),_rgba(2,6,23,0.92)_58%)]"></div>
            <div class="absolute -top-24 right-[-8rem] h-72 w-72 rounded-full bg-cyan-300/10 blur-3xl"></div>
            <div class="absolute -bottom-20 left-[-7rem] h-72 w-72 rounded-full bg-emerald-300/10 blur-3xl"></div>

            <div class="relative mx-auto min-h-screen w-full max-w-md px-4 py-5">
                <header class="mb-4 rounded-2xl border border-white/10 bg-slate-900/75 px-4 py-3 backdrop-blur">
                    <p class="text-xs uppercase tracking-[0.22em] text-cyan-200">English Tutor</p>
                    <h1 class="mt-1 text-lg font-semibold text-white">{{ auth()->user()->name }}</h1>
                </header>

                <main class="rounded-3xl border border-white/10 bg-slate-900/75 p-5 shadow-2xl backdrop-blur">
                    {{ $slot }}
                </main>
            </div>

            <nav class="fixed inset-x-0 bottom-0 z-30 mx-auto w-full max-w-md border-t border-white/10 bg-slate-900/90 px-4 pb-[max(env(safe-area-inset-bottom),12px)] pt-3 backdrop-blur">
                <div class="flex items-center gap-2">
                    <a
                        href="{{ route('chat') }}"
                        wire:navigate
                        class="flex h-11 flex-1 items-center justify-center rounded-2xl text-sm font-semibold {{ request()->routeIs('chat') ? 'bg-cyan-300 text-slate-950' : 'bg-slate-800/80 text-slate-100' }}"
                    >
                        Chat
                    </a>
                    <a
                        href="{{ route('profile') }}"
                        wire:navigate
                        class="flex h-11 flex-1 items-center justify-center rounded-2xl text-sm font-semibold {{ request()->routeIs('profile') ? 'bg-emerald-300 text-slate-950' : 'bg-slate-800/80 text-slate-100' }}"
                    >
                        Profile
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="flex h-11 w-full items-center justify-center rounded-2xl bg-rose-400/15 text-sm font-semibold text-rose-200">
                            Sign out
                        </button>
                    </form>
                </div>
            </nav>
        </div>

        @livewireScripts
    </body>
</html>
