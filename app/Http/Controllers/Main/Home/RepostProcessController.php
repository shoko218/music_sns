<?php

namespace App\Http\Controllers\Main\Home;

use App\Http\Controllers\Controller;
use App\Model\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\Notice_log;

class RepostProcessController extends Controller
{
    public function __invoke(Request $request){
        if(!$request->reposted){//拡散する場合
            $new_post=new Post;
            $new_post->user_id=Auth::user()->id;
            $new_post->repost_id=$request->post_id;
            $new_post->save();
            $action=1;
        }else{//拡散を取り消す場合
            Post::where('user_id',Auth::user()->id)
            ->where('repost_id',$request->post_id)
            ->first()
            ->delete();
            $action=0;
        }

        $post=Post::with('user')
        ->with('like_post_logs')
        ->find($request->post_id);

        $notice_log = Notice_log::where('from_user_id',Auth::user()->id)->where('to_user_id',$post['user_id'])->where('action_type',2)->where('target_post_id',$post['id'])->first();

        if($action == 1 && $notice_log == null && Auth::user()->id != $post['user_id'] ){
            Notice_log::create(['from_user_id' => Auth::user()->id , 'to_user_id'=>$post['user_id'] , 'action_type'=> 2 , 'target_post_id' => $post['id']]);
        }

        $param=['action'=>$action];
        return $param;
    }
}
