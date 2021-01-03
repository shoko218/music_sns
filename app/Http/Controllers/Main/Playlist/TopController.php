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
        $playlists=Playlist::with('user')
        ->with('like_playlist_logs')
        ->whereIn('user_id', $follow_ids)
        ->orwhere('user_id',Auth::user()->id)
        ->orderby('id','desc')
        ->get();
        $filtered_playlists=$playlists->filter(function($playlist){
            return (($playlist['repost_id'] == null) || ($playlist['repost_id'] != null && $playlist['user_id'] != Auth::user()->id && $playlist['repost']['user']['follow'] == false && $playlist['repost']['user']['id'] != Auth::user()->id));
        })->values();
        //リツイートじゃない or リツイートかつ自分のリツイートではないかつリツイート先がフォロワーと自分ではない
        $param=['playlists' => $filtered_playlists];
        return view('main.playlist.top',$param);
    }
}
