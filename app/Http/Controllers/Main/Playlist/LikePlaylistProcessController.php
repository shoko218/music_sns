<?php

namespace App\Http\Controllers\Main\Playlist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Playlist;
use App\Model\LikePlaylistLog;
use Illuminate\Support\Facades\Auth;

class LikePlaylistProcessController extends Controller
{
    public function __invoke(Request $request){
        if($request->playlist_id!=null){
            $log=LikePlaylistLog::where('user_id',Auth::user()->id)->where('playlist_id',$request->playlist_id)->first();
            if($log==null){//いいねしていない投稿が対象の場合
                LikePlaylistLog::create(['user_id' => Auth::user()->id,'playlist_id' => $request->playlist_id]);
                $action = 1;
            }else{//いいねしている投稿が対象の場合
                $log->delete();
                $action = 0;
            }
        }else{
            $action = -1;
        }

        $playlist=Playlist::with('user')
        ->with('like_playlist_logs')
        ->find($request->playlist_id);

        $param=['like_playlist_logs'=>$playlist['like_playlist_logs'],'action' => $action];
        return $param;
    }
}
