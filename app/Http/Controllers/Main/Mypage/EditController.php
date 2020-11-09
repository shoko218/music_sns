<?php

namespace App\Http\Controllers\Main\Mypage;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EditController extends Controller
{
    public function __invoke(){
        $user=User::find(Auth::user()->id);
        $param=['user'=>$user];
        return view('main.mypage.edit',$param);
    }
}
