<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::home')->name('home');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::livewire('/privacy', 'pages::privacy')->name('privacy');
Route::livewire('/crew', 'pages::crew')->name('crew');
Route::livewire('/crew-apply', 'pages::crew-apply')->name('crew-apply');
Route::livewire('/login', 'pages::auth.phone-login')->name('login');
Route::livewire('/apply', 'pages::party.apply')->name('apply');
 
Route::livewire('/admin/login', 'pages::admin-auth.login')->name('admin.login');

Route::middleware('auth')->group(function () {
    Route::livewire('/mypage', 'pages::mypage')->name('mypage');
    Route::livewire('/party/attendees', 'pages::party.attendees')->name('party.attendees');
    Route::livewire('/signals/received', 'pages::signals.received')->name('signals.received');
    Route::livewire('/signals/matches', 'pages::signals.matches')->name('signals.matches');
});


Route::middleware(['auth', 'admin'])->group(function () {
    Route::livewire('/admin/dashboard', 'pages::admin.dashboard')->name('admin.dashboard');
    Route::livewire('/admin/today', 'pages::admin.today')->name('admin.today');
    Route::livewire('/admin/attendees', 'pages::admin.attendees')->name('admin.attendees');
    Route::livewire('/admin/checkin', 'pages::admin.checkin')->name('admin.checkin');
    Route::livewire('/admin/events', 'pages::admin.events')->name('admin.events');
    Route::livewire('/admin/locations', 'pages::admin.locations')->name('admin.locations');
    Route::livewire('/admin/categories', 'pages::admin.categories')->name('admin.categories');
    Route::livewire('/admin/users', 'pages::admin.users')->name('admin.users');
    Route::livewire('/admin/signals', 'pages::admin.signals')->name('admin.signals');
    Route::livewire('/admin/settings', 'pages::admin.settings')->name('admin.settings');
     Route::livewire('/admin/reviews', 'pages::admin.reviews')->name('admin.reviews');
});

require __DIR__ . '/settings.php';
