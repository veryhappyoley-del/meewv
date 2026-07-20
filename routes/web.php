<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::livewire('/login', 'pages::auth.phone-login')->name('login');
Route::livewire('/apply', 'pages::party.apply')->name('apply');
Route::livewire('/admin/attendees', 'pages::admin.attendees')->name('admin.attendees');
Route::livewire('/admin/checkin', 'pages::admin.checkin')->name('admin.checkin');
Route::livewire('/party/attendees', 'pages::party.attendees')->name('party.attendees');
Route::livewire('/signals/received', 'pages::signals.received')->name('signals.received');
Route::livewire('/signals/matches', 'pages::signals.matches')->name('signals.matches');
require __DIR__.'/settings.php';
