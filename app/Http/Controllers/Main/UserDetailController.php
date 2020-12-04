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
        $param=['user'=>$user];
        return view('main.user_detail',$param);
    }
}
