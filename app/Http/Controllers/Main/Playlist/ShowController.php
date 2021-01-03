<?php

namespace App\Http\Controllers\Main\Playlist;

use App\Http\Controllers\Controller;
use App\Model\Playlist;
use App\Model\PlaylistLog;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function __invoke($playlist_id){
        $playlist=Playlist::with('user')
        ->with('playlist_logs')
        ->with('like_playlist_logs')
        ->find($playlist_id);
        if($playlist!=null){
            if ($playlist['repost_id']==null) {//普通のプレイリストなら
                $params=['playlist'=>$playlist,'repost_user_name' => null];
            }else{
                $params=['playlist'=>$playlist['repost'],'repost_user_name' => $playlist['user']['name']];
            }
            return view('main.playlist.detail',$params);
        }else{
            return redirect ('/playlist');
        }
    }
}
