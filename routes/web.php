<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if(Session::has('user_id')){

        return redirect('file-list');
    }else{
        return view('login');
    }
});
Route::group(['middleware' => ['logcheck']], function () {
    Route::get('file-list', 'AdminController@show_list');
    Route::get('edit_images', 'AdminController@edit_images');
    Route::get('get_images', 'AdminController@get_images');
    Route::get('remove_file', 'AdminController@remove_file');
    Route::get('show_images', 'AdminController@show_images');
    Route::get('approve', 'AdminController@approve');
    Route::get('imagefiles/{id}', 'AdminController@imagefiles');
});
Route::get('login_view', 'Login@login_view');
Route::post('login', 'Login@login');
Route::get('/login_view', function() {
    if(Session::has('user_id')){

        return redirect('file-list');
    }else{
        return view('login');
    }
});

Route::get('/logout', function() {
    if(Session::has('user_id')){
       Session::pull('user_id');
       Session::pull('name');
       return view('login');
    }
});








