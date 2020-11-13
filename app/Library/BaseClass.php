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


class BaseClass{//共用関数クラス
    public static function getMusic($track_id){//itunes APIで楽曲idからitunes上にある楽曲情報を取り出し、値を返す関数
        $url="http://ax.itunes.apple.com/WebObjects/MZStoreServices.woa/wa/wsLookup?id=".$track_id."&country=JP";
        $json = file_get_contents($url);
        $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $arr = json_decode($json,true);
        $music_info=array(
            'title'=>$arr['results'][0]['trackName'],//タイトル
            'artist'=>$arr['results'][0]['artistName'],//アーティスト名
            'artwork_url'=>$arr['results'][0]['artworkUrl100'],//CDジャケットの画像
            'music_url'=>$arr['results'][0]['previewUrl'],//試聴音源のURL
            'itunes_url'=>$arr['results'][0]['trackViewUrl'],//楽曲のiTunesページ
            'track_id'=>$arr['results'][0]['trackId'],//楽曲id
        );
        return $music_info;
    }

    public static function SearchMusic($word){//itunes APIを使ってitunes上にある楽曲を検索ワードから検索し、検索結果を返す関数
        $replace_words=array(" ","　");//空白は検索の際に受け付けられないので代用となる"+"に変換
        $word=str_replace($replace_words,"+",$word);
        $url="http://ax.itunes.apple.com/WebObjects/MZStoreServices.woa/wa/wsSearch?term=".$word."&media=music&country=jp&lang=ja_jp&limit=200";
        $json = file_get_contents($url);
        $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $arr = json_decode($json,true);
        if($arr['results']!=null){
            foreach ($arr['results'] as $index => $result) {
                $results[$index]=array(
                    'title'=>$result['trackName'],//タイトル
                    'artist'=>$result['artistName'],//アーティスト名
                    'artwork_url'=>$result['artworkUrl100'],//CDジャケットの画像
                    'music_url'=>$result['previewUrl'],//試聴音源のURL
                    'track_id'=>$result['trackId']//楽曲id
                );
            }
        }else{
            $results[0]=array('trackId'=>-1);
        }
        return $results;
    }
}
