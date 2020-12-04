<?php

namespace App\Http\Controllers\Main\Home;

use App\Http\Controllers\Controller;
use App\Model\Post;
use Illuminate\Http\Request;

class TopController extends Controller
{
    public function __invoke(Request $request){//ホーム
        $posts=Post::join('users','users.id','=','posts.user_id')//ユーザー情報と投稿情報をまとめて取得
        ->select('posts.id as post_id','user_id','contents','img_path','music_track_id','music_title','music_artist','music_artwork','music_url','music_itunes_url','repost_id','reply_post_id','name','user_name','icon_path')
        ->orderby('posts.id','desc')
        ->get();
        $param=['posts'=>$posts];
        return view('main.home.top',$param);
    }
}
