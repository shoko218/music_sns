<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Library\BaseClass;
use App\Model\Post;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\Fflog;

class UserDetailController extends Controller
{
    public function __invoke($user_name){//ユーザー情報を表示
        $user=User::select('*')
        ->where('user_name','=',$user_name)
        ->first();
        if($user==null){//該当するユーザーが存在しなければ
            return redirect('/home');
        }
        $posts=Post::join('users','users.id','=','posts.user_id')//ユーザー情報と投稿情報をまとめて取得
        ->select('posts.id as post_id','user_id','contents','img_path','music_track_id','music_title','music_artist','music_artwork','music_url','music_itunes_url','repost_id','reply_post_id','name','user_name','icon_path')
        ->where('user_id','=',$user->id)
        ->orderby('posts.id','desc')
        ->get();
        $follow_log=Fflog::select('*')->where('to_user_id','=',$user->id)->where('from_user_id','=',Auth::user()->id)->first();
        if($follow_log!=null){
            $is_follow=true;
        }else{
            $is_follow=false;
        }
        $param=['user'=>$user,'posts'=>$posts,'is_follow'=>$is_follow];
        return view('main.user.user_detail',$param);
    }
}
