<?php

use App\Mail\TestMarkdown;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Emails
Route::get('/email', function () {
    Mail::to('dm514821@gmail.com')->send(new TestMarkdown());
});