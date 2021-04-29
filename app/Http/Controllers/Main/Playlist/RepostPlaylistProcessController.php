<?php

namespace App\Http\Controllers\Main\Playlist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Playlist;
use Illuminate\Support\Facades\Auth;
use App\Model\Notice_log;

class RepostPlaylistProcessController extends Controller
{
    public function __invoke(Request $request){
        if(!$request->reposted){//拡散する場合
            $new_playlist=new Playlist;
            $new_playlist->user_id=Auth::user()->id;
            $new_playlist->repost_id=$request->playlist_id;
            $new_playlist->save();
            $action=1;
        }else{//拡散を取り消す場合
            Playlist::where('user_id',Auth::user()->id)
            ->where('repost_id',$request->playlist_id)
            ->first()
            ->delete();
            $action=0;
        }

        $playlist=Playlist::with('user')
        ->with('like_playlist_logs')
        ->find($request->playlist_id);

        $notice_log = Notice_log::where('from_user_id',Auth::user()->id)->where('to_user_id',$playlist['user_id'])->where('action_type',2)->where('target_playlist_id',$playlist['id'])->first();

        if($action == 1 && $notice_log == null && Auth::user()->id != $playlist['user_id']){
            Notice_log::create(['from_user_id' => Auth::user()->id , 'to_user_id' => $playlist['user_id'] , 'action_type' => 2, 'target_playlist_id' => $playlist['id']]);
        }

        $param=['action'=>$action];
        return $param;
    }
}
