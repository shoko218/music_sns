<?php

namespace App\Http\Controllers\Main\Playlist;

use App\Http\Controllers\Controller;
use App\Library\BaseClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\Playlist;
use App\Model\PlaylistLog;

class CreateProcessController extends Controller
{
    public function __invoke(Request $request){
        $this->validate($request,Playlist::$rules);
        $playlist=Playlist::create(['user_id' => Auth::user()->id,'title' => $request->title,'description' => $request->description]);
        foreach ($request->track_ids as $t) {
            $infos=BaseClass::getMusic($t);
            PlaylistLog::create(['playlist_id' => $playlist->id,'music_track_id' => $t,'music_title' => $infos['title'],'music_artist' => $infos['artist'],'music_artwork' => $infos['artwork_url'],'music_itunes_url' => $infos['itunes_url'],'music_url' => $infos['music_url']]);
        }
        return redirect('/playlist')->with('suc_msg','プレイリストを投稿しました。');
    }
}
