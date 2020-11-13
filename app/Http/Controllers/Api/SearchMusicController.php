<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Library\BaseClass;
use Illuminate\Http\Request;

class SearchMusicController extends Controller
{
    public function __invoke(Request $request){//itunes APIを使ってitunes上にある楽曲を検索ワードから検索し、検索結果を返す
        $word=$request->input('word');
        $results=BaseClass::SearchMusic($word);
        $param = ['results'=>$results];
        return $param;
    }
}
