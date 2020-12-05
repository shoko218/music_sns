<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Post;

class SearchDataController extends Controller
{
    public function __invoke(Request $request){
        $temps=explode(" ", $request->keyword);
        $keywords=[];
        $mainQuery = Post::join('users','users.id','=','posts.user_id')//ユーザー情報と投稿情報をまとめて取得
        ->select('posts.id as post_id','user_id','contents','img_path','music_track_id','music_title','music_artist','music_artwork','music_url','music_itunes_url','repost_id','reply_post_id','name','user_name','icon_path');
        $columns=['contents','music_title','music_artist'];
        foreach ($temps as $key =>$temp) {
            $keywords[$key]=array(//ある程度の表記揺れに対応
                "normal"=>$temp,
                "hiragana"=>mb_convert_kana($temp, "c"),
                "katakana"=>mb_convert_kana($temp, "C"),
                "lower"=>strtolower($temp),
                "upper"=>strtoupper($temp),
            );
        }
        $mainQuery->where(function ($keywordSQL) use ($keywords,$columns) {
            foreach ($keywords as $keyword) {
                foreach ($columns as $column) {
                    $keywordSQL->orwhere($column, 'like', '%'.$keyword['normal'].'%');
                    $keywordSQL->orwhere($column, 'like', '%'.$keyword['hiragana'].'%');
                    $keywordSQL->orwhere($column, 'like', '%'.$keyword['katakana'].'%');
                    $keywordSQL->orwhere($column, 'like', '%'.$keyword['lower'].'%');
                    $keywordSQL->orwhere($column, 'like', '%'.$keyword['upper'].'%');
                }
            }
        });
        $results=$mainQuery->orderBy('posts.id', 'desc')->get();
        $param=['results'=>$results];
        return $param;
    }
}
