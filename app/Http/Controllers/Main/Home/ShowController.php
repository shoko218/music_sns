<?php

namespace App\Http\Controllers\Main\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Post;

class ShowController extends Controller
{
    public function __invoke(Request $request){
        $post=Post::with('user')
        ->with('like_post_logs')
        ->find($request->post_id);
        if($post!=null){
            if($post['repost_id']==null){//普通の投稿なら
                $params=['post' => $post,'repost_user_name' => null];
            }else{//拡散投稿なら
                $params=['post' => $post['repost'],'repost_user_name' => $post['user']['name']];
            }
            return view('main.home.detail',$params);
        }else{
            return redirect ('/home');
        }
    }
}
