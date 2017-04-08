<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
Auth::routes();

Route::get('/', 'HomeController@index');
Route::get('/challenge/{id}', 'HomeController@challenge');
Route::post('/submit', 'HomeController@submit');
Route::get('/create', 'HomeController@create');
Route::get('/view', 'HomeController@view');
Route::post('/submitCreate', 'HomeController@submitCreate');
Route::post('/commitCode', 'HomeController@commitCode');
Route::get('/challenge/solution/{id}', 'HomeController@challengeSolution');
Route::get('/home', function () {
        return redirect('/view');
});
