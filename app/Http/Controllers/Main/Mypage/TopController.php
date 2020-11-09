<?php

namespace App\Http\Controllers\Main\Mypage;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopController extends Controller
{
    public function __invoke(Request $request){
        $user=User::find(Auth::user()->id);
        $param=['user'=>$user];
        return view('main.mypage.top',$param);
    }
}
