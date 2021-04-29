<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Post;

class SearchPostController extends Controller
{
    public function __invoke(Request $request){
        if($request->keyword!=null){
            $temps=explode(" ", $request->keyword);
            $keywords=[];
            $mainQuery = Post::with('user')
            ->with('like_post_logs')
            ->where('repost_id',null);
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
        }elseif($request->key_music_id!=null){
            $results = Post::with('user')
            ->with('like_post_logs')
            ->where('repost_id',null)
            ->where('music_track_id',$request->key_music_id)
            ->orderBy('posts.id', 'desc')
            ->get();
        }
        $param=['results'=>$results];
        return $param;
    }
}
