<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');

Route::middleware('auth')->group(function () {
    Route::view('/', 'contents.dashboard.index')->name('dashboard');

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('', \App\Livewire\Users\Table::class)->name('index');
        Route::get('create', \App\Livewire\Users\CreatePage::class)->name('create');
        Route::get('{user}/edit', \App\Livewire\Users\UpdatePage::class)->name('edit');
    });

    Route::prefix('waste-types')->name('waste-types.')->group(function () {
        Route::get('', \App\Livewire\WasteTypes\Table::class)->name('index');
        Route::get('create', \App\Livewire\WasteTypes\CreatePage::class)->name('create');
        Route::get('{wasteType}/edit', \App\Livewire\WasteTypes\UpdatePage::class)->name('edit');
    });

    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('', \App\Livewire\Clients\Table::class)->name('index');
        Route::get('create', \App\Livewire\Clients\CreatePage::class)->name('create');
        Route::get('{client}/edit', \App\Livewire\Clients\UpdatePage::class)->name('edit');
        Route::get('{client}', \App\Livewire\Clients\ViewPage::class)->name('view');
    });

    Route::prefix('price-lists')->name('price-lists.')->group(function () {
        Route::get('', \App\Livewire\PriceLists\Table::class)->name('index');
        Route::get('create', \App\Livewire\PriceLists\CreatePage::class)->name('create');
        Route::get('{priceList}/edit', \App\Livewire\PriceLists\UpdatePage::class)->name('edit');
    });

    Route::prefix('pickups')->name('pickups.')->group(function () {
        Route::get('', \App\Livewire\Pickups\Table::class)->name('index');
        Route::get('create', \App\Livewire\Pickups\CreatePage::class)->name('create');
        Route::get('{pickup}/edit', \App\Livewire\Pickups\UpdatePage::class)->name('edit');
        Route::get('{pickup}', \App\Livewire\Pickups\ViewPage::class)->name('view');
        Route::get('{pickup}/boxes/create', \App\Livewire\Boxes\CreatePage::class)->name('boxes.create');
    });

    Route::prefix('boxes')->name('boxes.')->group(function () {
        Route::get('{pickupBox}/edit', \App\Livewire\Boxes\UpdatePage::class)->name('edit');
        Route::get('{pickupBox}', \App\Livewire\Boxes\ViewPage::class)->name('show');
    });

    Route::view('routes', 'contents.routes.index')->name('routes.index');
});
