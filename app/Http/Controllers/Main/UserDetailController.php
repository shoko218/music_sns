<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Library\BaseClass;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDetailController extends Controller
{
    public function __invoke($user_name){//ユーザー情報を表示
        $user=User::select('*')
        ->where('user_name','=',$user_name)
        ->first();
        if($user==null){//該当するユーザーが存在しなければ
            return redirect('/home');
        }
        if($user->my_music_track_id!=null){//該当するユーザーがイチオシ音楽を登録していなければ
            $music_info=BaseClass::getMusic($user->my_music_track_id);
        }else{//該当ユーザーがイチオシ音楽を登録していれば
            $music_info=null;
        }
        $param=['user'=>$user,'music_info'=>$music_info];
        return view('main.user_detail',$param);
    }
}
