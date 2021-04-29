<?php

namespace App\Http\Controllers\Main\Playlist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Playlist;
use App\Model\LikePlaylistLog;
use Illuminate\Support\Facades\Auth;
use App\Model\Notice_log;

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

        $notice_log = Notice_log::where('from_user_id',Auth::user()->id)->where('to_user_id',$playlist['user_id'])->where('action_type',3)->where('target_playlist_id',$playlist['id'])->first();

        if($action == 1 && $notice_log == null && Auth::user()->id != $playlist['user_id']){
            Notice_log::create(['from_user_id' => Auth::user()->id , 'to_user_id'=>$playlist['user_id'] , 'action_type'=>3 , 'target_playlist_id' => $playlist['id']]);
        }

        $param=['like_playlist_logs'=>$playlist['like_playlist_logs'],'action' => $action];
        return $param;
    }
}
