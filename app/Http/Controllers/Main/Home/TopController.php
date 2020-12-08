<?php

namespace App\Http\Controllers\Main\Home;

use App\Http\Controllers\Controller;
use App\Model\Fflog;
use App\Model\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopController extends Controller
{
    public function __invoke(Request $request){//ホーム
        $follow_ids=Fflog::select('to_user_id')->where('from_user_id',Auth::user()->id)->get();
        $follow_ids[]=Auth::user()->id;
        $posts=Post::join('users','users.id','=','posts.user_id')//ユーザー情報と投稿情報をまとめて取得
        ->select('posts.id as post_id','user_id','contents','img_path','music_track_id','music_title','music_artist','music_artwork','music_url','music_itunes_url','repost_id','reply_post_id','name','user_name','icon_path')
        ->whereIn('user_id', $follow_ids)
        ->orderby('posts.id','desc')
        ->get();
        $param=['posts'=>$posts];
        return view('main.home.top',$param);
    }
}
