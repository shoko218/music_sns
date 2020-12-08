<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Library\BaseClass;
use Illuminate\Http\Request;
use App\Model\User;
use Illuminate\Support\Facades\Auth;

class SetMyMusicController extends Controller
{
    public function __invoke(Request $request){//自分のイチオシ音楽を設定する
        $user=User::find(Auth::user()->id);
        $new_music_info=BaseClass::getMusic($request->track_id);
        $user->update([
            'my_music_track_id'=>$request->track_id,
            'my_music_title'=>$new_music_info['title'],
            'my_music_artist'=>$new_music_info['artist'],
            'my_music_artwork'=>$new_music_info['artwork_url'],
            'my_music_url'=>$new_music_info['music_url'],
            'my_music_itunes_url'=>$new_music_info['itunes_url'],
        ]);
        $param=['track_id'=>$request->track_id];
        return $param;
    }
}
