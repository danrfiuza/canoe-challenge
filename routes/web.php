<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('funds', function () {
    return Inertia::render('Funds');
})->name('funds');
