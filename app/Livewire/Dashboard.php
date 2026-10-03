<?php

namespace App\Livewire;

use App\Models\CleaningLogs;
use App\Models\SensorReadings;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    public bool $showCleaningConfirmation = false;

    public function recordCleaning(): void
    {
        CleaningLogs::create([
            'user_id' => Auth::id(),
            'cleaned_at' => now(),
        ]);

        $this->showCleaningConfirmation = false;
        session()->flash('cleaning-success', 'Cleaning recorded. The restroom counter will reset when the device checks in.');
    }

    public function render(): View
    {
        return view('livewire.dashboard', [
            'reading' => SensorReadings::query()->latest('id')->first(),
            'lastCleaning' => CleaningLogs::query()->latest('cleaned_at')->first(),
        ])->layout('layouts.app');
    }
}
