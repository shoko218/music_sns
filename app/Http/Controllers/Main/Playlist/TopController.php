<?php

namespace App\Http\Controllers\Main\Playlist;

use App\Http\Controllers\Controller;
use App\Model\Fflog;
use App\Model\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopController extends Controller
{
    public function __invoke(Request $request){
        $follow_ids=Fflog::select('to_user_id')->where('from_user_id',Auth::user()->id)->get();
        $follow_ids[]=Auth::user()->id;
        $playlists=Playlist::with('user')
        ->whereIn('user_id', $follow_ids)
        ->orderby('id','desc')
        ->get();
        $param=['playlists'=>$playlists];
        return view('main.playlist.top',$param);
    }
}
