<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GetMusicController extends Controller
{
    public function __invoke(Request $request){
        $track_id=$request->input('track_id');
        $url="http://ax.itunes.apple.com/WebObjects/MZStoreServices.woa/wa/wsLookup?id=".$track_id."&country=JP";
        $json = file_get_contents($url);
        $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $arr = json_decode($json,true);
        $result=$arr['results'][0];
        $param = ['result'=>$result];
        return $param;
    }
}
