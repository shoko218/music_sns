<?php

namespace App\Http\Controllers\Main\Playlist;

use App\Http\Controllers\Controller;
use App\Model\Playlist;
use App\Model\PlaylistLog;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function __invoke($playlist_id){
        $playlist=Playlist::with('user')->with('playlist_logs')->find($playlist_id);
        $param=['playlist'=>$playlist];
        return view('main.playlist.detail',$param);
    }
}
