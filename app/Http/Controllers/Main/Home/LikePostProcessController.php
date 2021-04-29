<?php

namespace App\Http\Controllers\Main\Home;

use App\Http\Controllers\Controller;
use App\Model\LikePostLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\Post;
use App\Model\Notice_log;

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

        $notice_log = Notice_log::where('from_user_id',Auth::user()->id)->where('to_user_id',$post['user_id'])->where('action_type',3)->where('target_post_id',$post['id'])->first();

        if($action == 1 && $notice_log == null && Auth::user()->id != $post['user_id'] ){
            Notice_log::create(['from_user_id' => Auth::user()->id , 'to_user_id'=>$post['user_id'] , 'action_type'=> 3 , 'target_post_id' => $post['id']]);
        }

        $param=['like_post_logs' => $post['like_post_logs'],'action' => $action];
        return $param;
    }
}
