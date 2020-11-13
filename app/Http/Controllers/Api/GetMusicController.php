<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\BaseClass;

class GetMusicController extends Controller
{
    public function __invoke(Request $request){
        $track_id=$request->input('track_id');
        $music_info=BaseClass::getMusic($track_id);
        $param = ['musicInfo'=>$music_info];
        return $param;
    }
}
