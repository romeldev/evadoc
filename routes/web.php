<?php

use Illuminate\Support\Facades\Route;
// Auth::routes();

Route::get('/check-student/{student_code}', 'CheckController@checkStudent');

Route::get('{any}', 'HomeController@index')
    ->where('any', '.*');
    // ->middleware('auth');

// Route::post('/api/users/{id}/update-profile', 'API\UserController@updateProfile')->middleware('auth');



