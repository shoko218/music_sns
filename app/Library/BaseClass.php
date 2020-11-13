<?php
namespace App\Library;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\Cart;
use App\Model\Order_log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use App\Model\Product;
use Illuminate\Database\Eloquent\Collection;


class BaseClass{
    public static function getMusic($track_id){
        $url="http://ax.itunes.apple.com/WebObjects/MZStoreServices.woa/wa/wsLookup?id=".$track_id."&country=JP";
        $json = file_get_contents($url);
        $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $arr = json_decode($json,true);
        $music_info=array(
            'title'=>$arr['results'][0]['trackName'],
            'artist'=>$arr['results'][0]['artistName'],
            'artwork_url'=>$arr['results'][0]['artworkUrl100'],
            'music_url'=>$arr['results'][0]['previewUrl'],
            'itunes_url'=>$arr['results'][0]['trackViewUrl'],
            'track_id'=>$arr['results'][0]['trackId']
        );
        return $music_info;
    }

    public static function SearchMusic($word){
        $replace_words=array(" ","　");
        $word=str_replace($replace_words,"+",$word);
        $url="http://ax.itunes.apple.com/WebObjects/MZStoreServices.woa/wa/wsSearch?term=".$word."&media=music&country=jp&lang=ja_jp&limit=200";
        $json = file_get_contents($url);
        $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $arr = json_decode($json,true);
        if($arr['results']!=null){
            foreach ($arr['results'] as $index => $result) {
                $results[$index]=array(
                    'title'=>$result['trackName'],
                    'artist'=>$result['artistName'],
                    'artwork_url'=>$result['artworkUrl100'],
                    'music_url'=>$result['previewUrl'],
                    'track_id'=>$result['trackId']
                );
            }
        }else{
            $results[0]=array('trackId'=>-1);
        }
        return $results;
    }
}
