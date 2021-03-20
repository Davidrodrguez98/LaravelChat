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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::get('auth/user', function() {
    if(auth()->check())
    {
        return response()->json([
            'authUser' => \App\Models\User::find(auth()->id())
        ]);
    }else
    {
        return null;
    }
});

Route::get('chat/{chat}/getMessages', 'App\Http\Controllers\ChatController@getMessages')->name('chat.getMessages');

Route::get('chat/{chat}/getUsers', 'App\Http\Controllers\ChatController@getUsers')->name('chat.getUsers');

Route::get('chat/with/{user}', 'App\Http\Controllers\ChatController@chatWith')->name('chat.with');

Route::get('chat/{chat}', 'App\Http\Controllers\ChatController@show')->name('chat.show');

Route::post('message/sent', 'App\Http\Controllers\MessageController@sent')->name('message.sent');
