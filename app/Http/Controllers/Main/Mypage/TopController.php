<?php

namespace App\Http\Controllers\Main\Mypage;

use App\Http\Controllers\Controller;
use App\Library\BaseClass;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopController extends Controller
{
    public function __invoke(Request $request){
        $user=User::find(Auth::user()->id);
        if($user->my_music_track_id!=null){
            $music_info=BaseClass::getMusic($user->my_music_track_id);
        }else{
            $music_info=null;
        }
        $param=['user'=>$user,'music_info'=>$music_info];
        return view('main.mypage.top',$param);
    }
}
