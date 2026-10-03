<div wire:poll.5s class="min-h-screen bg-zinc-50">
    @php
        $waterState = $reading?->water_state ?? 'uncalibrated';
        $airState = $reading?->mq135_state ?? 'uncalibrated';
        $fresh = $reading && $reading->created_at?->greaterThan(now()->subSeconds(45));
        $waterLabel = [
            'dry' => 'Dry',
            'clear_water' => 'Water detected',
            'muddy_water' => 'Muddy water detected',
            'uncalibrated' => 'Waiting for setup',
        ][$waterState] ?? 'Waiting for setup';
        $airLabel = [
            'normal' => 'Air is okay',
            'elevated' => 'Odor needs attention',
            'uncalibrated' => 'Waiting for setup',
        ][$airState] ?? 'Waiting for setup';
        $waterTone = match ($waterState) {
            'dry' => 'bg-emerald-100 text-emerald-800',
            'clear_water' => 'bg-sky-100 text-sky-800',
            'muddy_water' => 'bg-amber-100 text-amber-900',
            default => 'bg-zinc-100 text-zinc-600',
        };
        $airTone = match ($airState) {
            'normal' => 'bg-emerald-100 text-emerald-800',
            'elevated' => 'bg-rose-100 text-rose-800',
            default => 'bg-zinc-100 text-zinc-600',
        };
        $cubicleOne = $reading?->cubicle_1_occupied;
        $cubicleTwo = $reading?->cubicle_2_occupied;
        $people = $reading?->people_count;
        if (! $fresh) {
            $overallLabel = 'Waiting for an update';
            $overallTone = 'border-zinc-200 bg-white text-zinc-800';
            $overallHint = 'Check that the monitoring device is powered and connected.';
        } elseif ($waterState === 'muddy_water' || $airState === 'elevated' || $people >= 20) {
            $overallLabel = 'Needs attention';
            $overallTone = 'border-rose-200 bg-rose-50 text-rose-900';
            $overallHint = 'Please check the restroom and take action as needed.';
        } elseif ($waterState === 'uncalibrated' || $airState === 'uncalibrated' || $people === null) {
            $overallLabel = 'Waiting for sensor setup';
            $overallTone = 'border-zinc-200 bg-white text-zinc-800';
            $overallHint = 'The device has not started reporting all restroom conditions yet.';
        } elseif ($waterState !== 'dry' || $airState !== 'normal' || $people >= 10) {
            $overallLabel = 'Please check conditions';
            $overallTone = 'border-amber-200 bg-amber-50 text-amber-900';
            $overallHint = 'A condition may need your attention soon.';
        } else {
            $overallLabel = 'Ready for use';
            $overallTone = 'border-emerald-200 bg-emerald-50 text-emerald-900';
            $overallHint = 'No issues have been reported by the monitoring device.';
        }
    @endphp

    <header class="border-b border-zinc-200 bg-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-5 py-5 sm:px-8">
            <div class="flex items-center gap-3">
                <div class="flex size-11 items-center justify-center rounded-2xl bg-zinc-950 text-white">
                    <svg viewBox="0 0 24 24" fill="none" class="size-6" aria-hidden="true"><path d="M5 5h14M7 5v4l-2 10h14L17 9V5M9 9h6M9 14h6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold tracking-tight sm:text-base">Restroom Monitoring</p>
                    <p class="text-xs text-zinc-500">Staff dashboard</p>
                </div>
            </div>
            <div class="flex items-center gap-2 sm:gap-3">
                <div class="flex items-center gap-2 rounded-full border border-zinc-200 bg-white px-3 py-2 text-xs font-semibold text-zinc-600">
                    <span class="size-2 rounded-full {{ $fresh ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                    {{ $fresh ? 'Up to date' : 'No recent update' }}
                </div>
                <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                    @csrf
                    <button class="rounded-xl border border-zinc-200 bg-white px-3 py-2 text-xs font-bold text-zinc-600 transition hover:border-zinc-400 hover:text-zinc-900">Sign out</button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-5 py-8 sm:px-8 sm:py-10">
        <section class="mb-6 flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
            <div>
                <p class="mb-2 text-xs font-bold uppercase tracking-[0.16em] text-zinc-500">Staff overview</p>
                <h1 class="font-display text-3xl font-extrabold tracking-[-0.04em] sm:text-4xl">How is the restroom?</h1>
                <p class="mt-2 text-sm text-zinc-600">Visits, cubicle availability, and current restroom conditions.</p>
            </div>
            <div class="flex flex-col items-start gap-3 sm:items-end">
                <button type="button" wire:click="$set('showCleaningConfirmation', true)" class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-zinc-950 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-zinc-800 focus:outline-none focus:ring-4 focus:ring-zinc-300">
                    <svg viewBox="0 0 24 24" fill="none" class="size-5" aria-hidden="true"><path d="m5 12 4 4L19 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Mark as cleaned
                </button>
                <p class="text-xs text-zinc-500">Updated {{ $reading?->created_at?->diffForHumans() ?? 'when the device connects' }}</p>
            </div>
        </section>

        <section class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4" aria-label="Current restroom conditions">
            <article class="rounded-[1.6rem] border border-zinc-200 bg-white p-6 shadow-sm sm:p-7">
                <div class="flex size-12 items-center justify-center rounded-2xl bg-zinc-100 text-zinc-800"><svg viewBox="0 0 24 24" fill="none" class="size-5" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m16 0v-2a4 4 0 0 0-3-3.87M14 3.13a4 4 0 0 1 0 7.75M10 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
                <p class="mt-6 text-sm font-semibold text-zinc-500">Visits since cleaning</p>
                <p class="mt-1 font-display text-5xl font-extrabold tracking-tight">{{ $people ?? '—' }} <span class="text-base font-semibold text-zinc-500">visits</span></p>
                <p class="mt-4 text-sm font-bold {{ is_numeric($people) && $people >= 20 ? 'text-rose-700' : (is_numeric($people) && $people >= 10 ? 'text-amber-700' : (is_numeric($people) ? 'text-emerald-700' : 'text-zinc-500')) }}">{{ is_numeric($people) ? ($people >= 20 ? 'Cleaning recommended' : ($people >= 10 ? 'Check soon' : 'Within normal range')) : 'Waiting for a reading' }}</p>
            </article>

            <article class="rounded-[1.6rem] border border-zinc-200 bg-white p-6 shadow-sm sm:p-7">
                <div class="flex size-12 items-center justify-center rounded-2xl bg-zinc-100 text-zinc-800"><svg viewBox="0 0 24 24" fill="none" class="size-5" aria-hidden="true"><path d="M4 21V5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v16M4 21h16M9 7h.01M15 7h.01M9 11h.01M15 11h.01M9 15h6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
                <p class="mt-6 text-sm font-semibold text-zinc-500">Cubicle availability</p>
                <div class="mt-4 grid grid-cols-2 gap-3">
                    @foreach ([['name' => 'Cubicle 1', 'occupied' => $cubicleOne], ['name' => 'Cubicle 2', 'occupied' => $cubicleTwo]] as $cubicle)
                        @php
                            $cubicleLabel = $cubicle['occupied'] === null ? 'Waiting for data' : ($cubicle['occupied'] ? 'In use' : 'Available');
                            $cubicleTone = $cubicle['occupied'] === null ? 'text-zinc-500' : ($cubicle['occupied'] ? 'text-amber-800' : 'text-emerald-700');
                            $cubicleDot = $cubicle['occupied'] === null ? 'bg-zinc-400' : ($cubicle['occupied'] ? 'bg-amber-500' : 'bg-emerald-500');
                        @endphp
                        <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-3">
                            <p class="text-xs font-semibold text-zinc-500">{{ $cubicle['name'] }}</p>
                            <p class="mt-2 flex items-center gap-2 text-sm font-bold {{ $cubicleTone }}"><span class="size-2 shrink-0 rounded-full {{ $cubicleDot }}"></span>{{ $cubicleLabel }}</p>
                        </div>
                    @endforeach
                </div>
                <p class="mt-4 text-xs leading-5 text-zinc-500">Live status for each cubicle.</p>
            </article>

            <article class="rounded-[1.6rem] border border-zinc-200 bg-white p-6 shadow-sm sm:p-7">
                <div class="flex size-12 items-center justify-center rounded-2xl bg-zinc-100 text-zinc-800"><svg viewBox="0 0 24 24" fill="none" class="size-5" aria-hidden="true"><path d="M12 3.5S5.5 11 5.5 15.2a6.5 6.5 0 1 0 13 0C18.5 11 12 3.5 12 3.5Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
                <p class="mt-6 text-sm font-semibold text-zinc-500">Floor water</p>
                <p class="mt-2 font-display text-3xl font-extrabold tracking-tight">{{ $waterLabel }}</p>
                <p class="mt-4 text-sm font-medium {{ $waterState === 'dry' ? 'text-emerald-700' : (in_array($waterState, ['clear_water', 'muddy_water'], true) ? 'text-amber-800' : 'text-zinc-500') }}">{{ $waterState === 'dry' ? 'Floor looks clear.' : (in_array($waterState, ['clear_water', 'muddy_water'], true) ? 'Please inspect the floor.' : 'Condition will show when set up.') }}</p>
            </article>

            <article class="rounded-[1.6rem] border border-zinc-200 bg-white p-6 shadow-sm sm:p-7">
                <div class="flex size-12 items-center justify-center rounded-2xl bg-zinc-100 text-zinc-800"><svg viewBox="0 0 24 24" fill="none" class="size-5" aria-hidden="true"><path d="M12 3v2m0 14v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M3 12h2m14 0h2M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
                <p class="mt-6 text-sm font-semibold text-zinc-500">Air and odor</p>
                <p class="mt-2 font-display text-3xl font-extrabold tracking-tight">{{ $airLabel }}</p>
                <p class="mt-4 text-sm font-medium {{ $airState === 'normal' ? 'text-emerald-700' : ($airState === 'elevated' ? 'text-rose-700' : 'text-zinc-500') }}">{{ $airState === 'normal' ? 'No odor alert.' : ($airState === 'elevated' ? 'Please check the restroom.' : 'Condition will show when set up.') }}</p>
            </article>
        </section>

        <section class="mb-6 grid gap-4 lg:grid-cols-[1.4fr_.6fr]">
            <article class="rounded-[1.75rem] border p-6 shadow-sm sm:flex sm:items-center sm:justify-between sm:gap-6 {{ $overallTone }}">
                <div class="flex items-start gap-4">
                    <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-white/80">
                        @if ($overallLabel === 'Ready for use')
                            <svg viewBox="0 0 24 24" fill="none" class="size-6" aria-hidden="true"><path d="m5 12 4 4L19 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        @else
                            <svg viewBox="0 0 24 24" fill="none" class="size-6" aria-hidden="true"><path d="M12 8v4m0 4h.01M10.3 3.9 2.8 17a2 2 0 0 0 1.7 3h15a2 2 0 0 0 1.7-3l-7.5-13.1a2 2 0 0 0-3.4 0Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.14em] opacity-70">Overall condition</p>
                        <h2 class="mt-1 text-2xl font-extrabold tracking-tight">{{ $overallLabel }}</h2>
                        <p class="mt-1 text-sm opacity-80">{{ $overallHint }}</p>
                    </div>
                </div>
                <p class="mt-5 text-xs font-medium opacity-70 sm:mt-0">Refreshes automatically</p>
            </article>

            <article class="flex flex-col justify-center rounded-[1.75rem] border border-zinc-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-[0.14em] text-zinc-500">Cleaning status</p>
                <p class="mt-2 font-display text-2xl font-extrabold tracking-tight">{{ $lastCleaning?->cleaned_at?->diffForHumans() ?? 'Not recorded' }}</p>
                <p class="mt-2 text-sm text-zinc-600">{{ $lastCleaning?->user?->name ? 'Recorded by '.$lastCleaning->user->name : 'No cleaning has been recorded yet.' }}</p>
            </article>
        </section>

        @if (session()->has('cleaning-success'))
            <div role="status" class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('cleaning-success') }}</div>
        @endif

        <footer class="pb-6 pt-8 text-center text-xs text-zinc-400">Conditions refresh automatically while this page is open.</footer>
    </main>

    @if ($showCleaningConfirmation)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/45 px-4 py-6" role="presentation" wire:click.self="$set('showCleaningConfirmation', false)">
            <section role="dialog" aria-modal="true" aria-labelledby="cleaning-dialog-title" class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl">
                <div class="flex size-12 items-center justify-center rounded-2xl bg-zinc-100 text-zinc-900"><svg viewBox="0 0 24 24" fill="none" class="size-6" aria-hidden="true"><path d="M5 5h14M7 5v4l-2 10h14L17 9V5M9 14h6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
                <h2 id="cleaning-dialog-title" class="mt-4 text-xl font-extrabold tracking-tight">Record this cleaning?</h2>
                <p class="mt-2 text-sm leading-6 text-zinc-600">This saves your name and the time. The device can then reset the visits-since-cleaning counter. Floor and air conditions will continue to follow the latest device readings.</p>
                <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                    <button type="button" wire:click="$set('showCleaningConfirmation', false)" class="rounded-xl border border-zinc-200 px-4 py-3 text-sm font-bold text-zinc-700 hover:bg-zinc-50">Go back</button>
                    <button type="button" wire:click="recordCleaning" wire:loading.attr="disabled" class="rounded-xl bg-zinc-950 px-4 py-3 text-sm font-bold text-white hover:bg-zinc-800 disabled:opacity-60"><span wire:loading.remove wire:target="recordCleaning">Yes, record cleaning</span><span wire:loading wire:target="recordCleaning">Saving…</span></button>
                </div>
            </section>
        </div>
    @endif
</div>
