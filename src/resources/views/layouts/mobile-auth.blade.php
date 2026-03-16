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
        <div class="relative min-h-screen overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(56,189,248,0.35),_rgba(2,6,23,0.9)_55%)]"></div>
            <div class="absolute -top-24 right-[-8rem] h-72 w-72 rounded-full bg-cyan-300/15 blur-3xl"></div>
            <div class="absolute -bottom-20 left-[-7rem] h-72 w-72 rounded-full bg-emerald-300/15 blur-3xl"></div>

            <main class="relative mx-auto flex min-h-screen w-full max-w-md items-center px-4 py-6">
                <div class="w-full rounded-3xl border border-white/10 bg-slate-900/75 p-5 shadow-2xl backdrop-blur">
                    {{ $slot }}
                </div>
            </main>
        </div>

        @livewireScripts
    </body>
</html>
