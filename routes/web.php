<?php

use App\Mail\TestMarkdown;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/test-email', function () {
   Mail::to('dm514822@gmail.com')->send(new TestMarkdown());
   return redirect()->route('welcome'); 
});