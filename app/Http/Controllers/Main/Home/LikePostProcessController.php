<?php

namespace App\Http\Controllers\Main\Home;

use App\Http\Controllers\Controller;
use App\Model\LikePostLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\Post;

class LikePostProcessController extends Controller
{
    public function __invoke(Request $request){
        if($request->post_id != null){
            $log=LikePostLog::where('user_id',Auth::user()->id)->where('post_id',$request->post_id)->first();
            if($log==null){//いいねしていない投稿が対象の場合
                LikePostLog::create(['user_id' => Auth::user()->id,'post_id' => $request->post_id]);
                $action = 1;
            }else{//いいねしている投稿が対象の場合
                $log->delete();
                $action = 0;
            }
        }else{
            $action = -1;
        }

        $post=Post::with('user')
        ->with('like_post_logs')
        ->find($request->post_id);

        $param=['like_post_logs' => $post['like_post_logs'],'action' => $action];
        return $param;
    }
}
