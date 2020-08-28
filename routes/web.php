<?php

use Illuminate\Support\Facades\Route;
// Auth::routes();

Route::get('/test', function() {

    // $menu = App\Model\Menu::whereNull('menu_id')->with('childrenMenus')->orderBy('order', 'asc')->get();
    // dd($menu);

    // cache()->forget( _const('CACHE_MENU') );
    cache()->forget( _const('CACHE_SCHOOLS') );

    // $menus = cache('menus');
    // dd($menus);
});


Route::get('/check-student/{student_code}', 'CheckController@checkStudent');

Route::get('{any}', 'HomeController@index')
    ->where('any', '.*');
    // ->middleware('auth');

// Route::post('/api/users/{id}/update-profile', 'API\UserController@updateProfile')->middleware('auth');


