<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class SetMyMusicController extends Controller
{
    public function __invoke(Request $request){//自分のイチオシ音楽を設定する
        $user=User::find(Auth::user()->id);
        $user->update(['my_music_track_id'=>$request->track_id]);
        $param=['track_id'=>$request->track_id];
        return $param;
    }
}
