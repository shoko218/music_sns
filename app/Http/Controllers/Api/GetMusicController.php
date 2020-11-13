<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\BaseClass;

class GetMusicController extends Controller
{
    public function __invoke(Request $request){//itunes APIで楽曲idからitunes上にある楽曲情報を取り出し、値を返す
        $track_id=$request->input('track_id');
        $music_info=BaseClass::getMusic($track_id);
        $param = ['musicInfo'=>$music_info];
        return $param;
    }
}
