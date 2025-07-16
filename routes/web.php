<?php

declare(strict_types=1);

use App\Http\Controllers\AiDemoController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
    
    // AI Demo Routes
    Route::prefix('ai')->name('ai.')->group(function () {
        Route::post('generate-text', [AiDemoController::class, 'generateText'])->name('generate-text');
        Route::post('generate-structured', [AiDemoController::class, 'generateStructuredData'])->name('generate-structured');
    });
});

require __DIR__.'/auth.php';
