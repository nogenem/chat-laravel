<?php

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
    if (Auth::check()) {
        return redirect()->route('chat.index');
    } else {
        return redirect()->route('login');
    }
});

Auth::routes();

Route::group(['prefix' => 'chat', 'middleware' => ['auth']], function () {
    Route::get('/', 'ChatController@index')->name('chat.index');
    Route::get('/getMessagesWithUser/{user_id}', 'ChatController@getMessagesWithUser')
        ->name('chat.getMessagesWithUser');
    Route::get('/getMessagesWithGroup/{group_id}', 'ChatController@getMessagesWithGroup')
        ->name('chat.getMessagesWithGroup');
    Route::post('/sendMessage', 'ChatController@sendMessage')->name('chat.sendMessage');
});