<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function __invoke(){
        if(Auth::check()){
            return redirect('/home');
        }else{
            return redirect('/login');
        }
    }
}
