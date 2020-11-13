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

Auth::routes();

Route::get('/', 'Main\IndexController');

Route::middleware(['auth'])->group(function () {
    Route::prefix('/home')->group(function () {
        Route::get('/', 'Main\Home\TopController');
        Route::get('/index', 'Main\Home\TopController');
    });
    Route::prefix('/notification')->group(function () {
        Route::get('/', 'Main\Notification\TopController');
    });
    Route::prefix('/playlist')->group(function () {
        Route::get('/', 'Main\Playlist\TopController');
    });
    Route::prefix('/dm')->group(function () {
        Route::get('/', 'Main\Playlist\TopController');
    });
    Route::prefix('/mypage')->group(function () {
        Route::get('/edit', 'Main\Mypage\EditController');
        Route::post('/edit_process', 'Main\Mypage\EditProcessController');
    });
    Route::get('/{user_name}', 'Main\UserDetailController');
});

Route::prefix('/api')->group(function(){
    Route::get('/get_music','Api\GetMusicController');
    Route::get('/search_music','Api\SearchMusicController');
    Route::post('/set_my_music','Api\SetMyMusicController');
    Route::post('/remove_my_music','Api\RemoveMyMusicController');
});

Route::get('/logout',function(){
    Auth::logout();
    return redirect('/');
});
