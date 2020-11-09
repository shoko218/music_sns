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
    Route::prefix('/mypage')->group(function () {
        Route::get('/', 'Main\Mypage\TopController');
        Route::get('/edit', 'Main\Mypage\EditController');
        Route::post('/edit_process', 'Main\Mypage\EditProcessController');
        Route::get('/select_music', 'Main\Mypage\SelectMusicController');
    });
});

Route::get('/search_music','Api\SearchMusicController');
Route::get('/get_music','Api\GetMusicController');

Route::get('/logout',function(){
    Auth::logout();
    return redirect('/');
});
