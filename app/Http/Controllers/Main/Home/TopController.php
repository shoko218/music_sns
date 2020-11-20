<?php

namespace App\Http\Controllers\Main\Home;

use App\Http\Controllers\Controller;
use App\Model\Post;
use Illuminate\Http\Request;

class TopController extends Controller
{
    public function __invoke(Request $request){
        $posts=Post::all()->reverse();
        $param=['posts'=>$posts];
        return view('main.home.top',$param);
    }
}
