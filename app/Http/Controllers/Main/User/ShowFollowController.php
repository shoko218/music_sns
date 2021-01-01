<?php

namespace App\Http\Controllers\Main\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Fflog;
use Illuminate\Support\Facades\Auth;

class ShowFollowController extends Controller
{
    public function __invoke($user_name){
        $user=User::where('user_name',$user_name)->first();
        $param=['user'=>$user];
        return view('main.user.user_follow',$param);
    }
}
