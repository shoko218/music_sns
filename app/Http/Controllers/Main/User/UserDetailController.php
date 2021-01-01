<?php

namespace App\Http\Controllers\Main\User;

use App\Http\Controllers\Controller;
use App\Library\BaseClass;
use App\Model\Post;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\Fflog;
use App\Model\PlaylistLog;
use App\Model\Playlist;

class UserDetailController extends Controller
{
    public function __invoke($user_name){//ユーザー情報を表示
        $user=User::select('*')
        ->where('user_name',$user_name)
        ->first();
        if($user==null){//該当するユーザーが存在しなければ
            return redirect('/home');
        }
        $posts=BaseClass::get_user_posts($user->id);

        $liked_posts=BaseClass::get_user_liked_posts($user->id);

        $playlists=BaseClass::get_user_playlists($user->id);

        $liked_playlists=BaseClass::get_user_liked_playlists($user->id);

        $follow_log=Fflog::select('*')->where('to_user_id','=',$user->id)->where('from_user_id','=',Auth::user()->id)->first();

        if($follow_log!=null){
            $is_follow=true;
        }else{
            $is_follow=false;
        }

        $param=['user'=>$user,'posts'=>$posts,'liked_posts'=>$liked_posts,'playlists'=>$playlists,'liked_playlists'=>$liked_playlists,'is_follow'=>$is_follow];

        return view('main.user.user_detail',$param);
    }
}
