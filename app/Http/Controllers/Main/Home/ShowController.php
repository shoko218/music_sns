<?php

namespace App\Http\Controllers\Main\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Post;
use Illuminate\Support\Collection;

class ShowController extends Controller
{
    public function __invoke(Request $request){
        $post=Post::with('user')
        ->with('like_post_logs')
        ->find($request->post_id);
        if($post!=null){
            if($post['repost_id']==null){//普通の投稿なら
                $replys=Post::with('user')
                ->with('like_post_logs')
                ->where('reply_post_id',$request->post_id)
                ->orderBy('id')
                ->get();
                $before_posts=collect();
                if($post->reply_post_id!=null){
                    $before_posts->push(Post::with('user')
                    ->with('like_post_logs')
                    ->where('id',$post->reply_post_id)
                    ->first());
                    while(1){
                        if($before_posts->last()->reply_post_id == null)break;
                        $before_posts->push(Post::with('user')
                        ->with('like_post_logs')
                        ->where('id',$before_posts->last()->reply_post_id)
                        ->first());
                    }
                    $before_posts=$before_posts->reverse()->values();
                }
                if($request->input('status')=="create_reply"){
                    $create_reply=true;
                }else{
                    $create_reply=false;
                }
                $params=['post' => $post,'repost_user_name' => null,'replys' => $replys,'before_posts' => $before_posts,'create_reply' => $create_reply];
            }else{//拡散投稿なら
                $replys=Post::with('user')
                ->with('like_post_logs')
                ->where('reply_post_id',$post['repost']->id)
                ->orderBy('id')
                ->get();
                $before_posts=collect();
                if($post['repost']->reply_post_id!=null){
                    $before_posts->push(Post::with('user')
                    ->with('like_post_logs')
                    ->where('id',$post['repost']->reply_post_id)
                    ->first());
                    while(1){
                        if($before_posts->last()->reply_post_id == null)break;
                        $before_posts->push(Post::with('user')
                        ->with('like_post_logs')
                        ->where('id',$before_posts->last()->reply_post_id)
                        ->first());
                    }
                    $before_posts=$before_posts->reverse()->values();
                }
                if($request->input('status')=="create_reply"){
                    $create_reply=true;
                }else{
                    $create_reply=false;
                }
                $params=['post' => $post['repost'],'repost_user_name' => $post['user']['name'],'replys' => $replys,'before_posts' => $before_posts,'create_reply' => $create_reply];
            }
            return view('main.home.detail',$params);
        }else{
            return redirect ('/home');
        }
    }
}
