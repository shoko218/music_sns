<?php

namespace App\Http\Controllers\Main\Home;

use App\Http\Controllers\Controller;
use App\Model\Fflog;
use App\Model\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopController extends Controller
{
    public function __invoke(Request $request){//ホーム
        $follow_ids=Fflog::select('to_user_id')->where('from_user_id',Auth::user()->id)->get();
        $follow_ids[]=Auth::user()->id;
        $posts=Post::with('user')
        ->with('like_post_logs')
        ->whereIn('user_id', $follow_ids)
        ->orderby('id','desc')
        ->get();
        $param=['posts'=>$posts];
        return view('main.home.top',$param);
    }
}
