<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign in · {{ config('app.name', 'Restroom Monitoring System') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-[#f4f4f2] px-5 py-10 font-sans text-[#151515] antialiased">
    <main class="w-full max-w-md">
        <div class="mb-7 flex flex-col items-center text-center">
            <div class="mb-4 flex size-14 items-center justify-center rounded-[1.2rem] bg-[#151515] text-white shadow-lg shadow-black/10">
                <svg viewBox="0 0 24 24" fill="none" class="size-7" aria-hidden="true"><path d="M5 5h14M7 5v4l-2 10h14L17 9V5M9 9h6M9 14h6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-zinc-500">Smart facility</p>
            <h1 class="mt-2 font-display text-3xl font-extrabold tracking-tight">Welcome back</h1>
            <p class="mt-2 text-sm text-zinc-500">Sign in to view restroom conditions.</p>
        </div>

        <form method="POST" action="{{ route('login.store') }}" class="rounded-[1.7rem] border border-zinc-200 bg-white p-6 shadow-[0_20px_70px_-35px_rgba(0,0,0,.3)] sm:p-8">
            @csrf
            <div>
                <label for="username" class="mb-2 block text-sm font-semibold">Username</label>
                <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus autocomplete="username" class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm outline-none transition placeholder:text-zinc-400 focus:border-zinc-800 focus:ring-4 focus:ring-zinc-900/5" placeholder="Enter your username">
                @error('username') <p class="mt-2 text-xs font-medium text-rose-700">{{ $message }}</p> @enderror
            </div>
            <div class="mt-5">
                <label for="password" class="mb-2 block text-sm font-semibold">Password</label>
                <input id="password" name="password" type="password" required autocomplete="current-password" class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm outline-none transition placeholder:text-zinc-400 focus:border-zinc-800 focus:ring-4 focus:ring-zinc-900/5" placeholder="Enter your password">
                @error('password') <p class="mt-2 text-xs font-medium text-rose-700">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="mt-7 flex w-full items-center justify-center gap-2 rounded-xl bg-[#151515] px-4 py-3 text-sm font-bold text-white transition hover:bg-zinc-800 focus:outline-none focus:ring-4 focus:ring-zinc-900/15">
                Sign in
                <svg viewBox="0 0 24 24" fill="none" class="size-4" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
        </form>
        <p class="mt-6 text-center text-xs text-zinc-400">Accounts are provided by the system administrator.</p>
    </main>
</body>
</html>
