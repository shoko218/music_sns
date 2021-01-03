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
        Route::post('/send_post_process', 'Main\Home\SendPostProcessController');
        Route::post('/delete_post_process', 'Main\Home\DeletePostProcessController');
        Route::post('/like_post_process', 'Main\Home\LikePostProcessController');
        Route::post('/repost_process', 'Main\Home\RepostProcessController');
        Route::prefix('/detail')->group(function () {
            Route::get('/{post_id}','Main\Home\ShowController');
            Route::get('/get_replies_chain/{post_id}','Main\Home\GetRepliesChainController');
            Route::post('/get_replies_chain','Main\Home\GetRepliesChainController');
            Route::post('/send_reply_process', 'Main\Home\SendReplyProcessController');
        });
    });
    Route::prefix('/notification')->group(function () {
        Route::get('/', 'Main\Notification\TopController');
    });
    Route::prefix('/search')->group(function () {
        Route::get('/', 'Main\Search\TopController');
    });
    Route::prefix('/playlist')->group(function () {
        Route::get('/', 'Main\Playlist\TopController');
        Route::get('/detail/{playlist_id}', 'Main\Playlist\ShowController');
        Route::get('/create', 'Main\Playlist\CreateController');
        Route::post('/create_process', 'Main\Playlist\CreateProcessController');
        Route::post('/delete_playlist_process', 'Main\Playlist\DeletePlaylistProcessController');
        Route::post('/like_playlist_process', 'Main\Playlist\LikePlaylistProcessController');
        Route::post('/repost_playlist_process', 'Main\Playlist\RepostPlaylistProcessController');
    });
    Route::prefix('/dm')->group(function () {
        Route::get('/', 'Main\DM\TopController');
    });
    Route::prefix('/mypage')->group(function () {
        Route::get('/edit', 'Main\Mypage\EditController');
        Route::post('/edit_process', 'Main\Mypage\EditProcessController');
    });
    Route::prefix('/user')->group(function () {
        Route::get('/{user_name}', 'Main\User\UserDetailController');
        Route::get('/{user_name}/follow', 'Main\User\ShowFollowController');
        Route::get('/{user_name}/follower', 'Main\User\ShowFollowerController');
    });
});

Route::prefix('/api')->group(function(){
    Route::get('/get_music', 'Api\GetMusicController');
    Route::get('/search_music', 'Api\SearchMusicController');
    Route::post('/set_my_music', 'Api\SetMyMusicController');
    Route::post('/remove_my_music', 'Api\RemoveMyMusicController');
    Route::post('/search_data', 'Api\SearchDataController');
    Route::post('/change_follow', 'Api\ChangeFollowController');
    Route::post('/get_follow_relations', 'Api\GetFollowRelationsController');
    Route::get('/get_user_posts', 'Api\GetUserPostsController');
    Route::get('/get_user_playlists', 'Api\GetUserPlaylistsController');
    Route::get('/get_user_liked_posts', 'Api\GetUserLikedPostsController');
    Route::get('/get_user_liked_playlists', 'Api\GetUserLikedPlaylistsController');
});

Route::get('/logout',function(){
    Auth::logout();
    return redirect('/');
});
