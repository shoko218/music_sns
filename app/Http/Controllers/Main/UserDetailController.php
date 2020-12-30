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
        $posts=Post::with('user')
        ->with('like_post_logs')
        ->where('user_id','=',$user->id)
        ->orderby('id','desc')
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
