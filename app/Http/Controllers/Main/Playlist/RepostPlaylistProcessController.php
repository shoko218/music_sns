<?php

namespace App\Http\Controllers\Main\Playlist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Playlist;
use Illuminate\Support\Facades\Auth;

class RepostPlaylistProcessController extends Controller
{
    public function __invoke(Request $request){
        if(!$request->reposted){//拡散する場合
            $playlist=new Playlist;
            $playlist->user_id=Auth::user()->id;
            $playlist->repost_id=$request->playlist_id;
            $playlist->save();
            $action=1;
        }else{//拡散を取り消す場合
            Playlist::where('user_id',Auth::user()->id)
            ->where('repost_id',$request->playlist_id)
            ->first()
            ->delete();
            $action=0;
        }
        $param=['action'=>$action];
        return $param;
    }
}
