<?php

use App\Http\Livewire\EventRegistration;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/events', function () {
    return view('events.index');
})->name('events.list');
Route::get('/events/register/{event?}',  function ($event = null) {
    return view('events.register',['event'=>$event]);
})->name('events.register');

/*Route::get('/events/{event}/register', function ($eventId) {
    return "Регистрация на мероприятие #{$eventId}";
})->name('event.register');*/

