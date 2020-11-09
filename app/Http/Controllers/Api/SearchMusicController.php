<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class SearchMusicController extends Controller
{
    public function __invoke(Request $request){
        $word=$request->input('word');
        $url="http://ax.itunes.apple.com/WebObjects/MZStoreServices.woa/wa/wsSearch?term=".$word."&media=music&country=jp&lang=ja_jp&limit=100";
        $json = file_get_contents($url);
        $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $arr = json_decode($json,true);
        foreach ($arr['results'] as $index => $result) {
            $results[$index]=array('title'=>$result['trackName'],'artist'=>$result['artistName'],'trackId'=>$result['trackId']);
        }
        $param = ['results'=>$results];
        return $param;
    }
}
