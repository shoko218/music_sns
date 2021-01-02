<?php

namespace App\Http\Controllers\Main\Home;

use App\Http\Controllers\Controller;
use App\Model\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RepostProcessController extends Controller
{
    public function __invoke(Request $request){
        if(!$request->reposted){//拡散する場合
            $post=new Post;
            $post->user_id=Auth::user()->id;
            $post->repost_id=$request->post_id;
            $post->save();
            $action=1;
        }else{//拡散を取り消す場合
            Post::where('user_id',Auth::user()->id)
            ->where('repost_id',$request->post_id)
            ->first()
            ->delete();
            $action=0;
        }
        $param=['action'=>$action];
        return $param;
    }
}
