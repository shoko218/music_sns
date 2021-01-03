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
        $user_id=Auth::user()->id;
        $follow_ids=Fflog::select('to_user_id')->where('from_user_id',$user_id)->get();
        $posts=Post::join('users','users.id','=','posts.user_id')
        ->select('posts.*')
        ->with('user')
        ->with('like_post_logs')
        ->where(function($query) use ($follow_ids){
            $query->whereIn('user_id', $follow_ids)
            ->orwhere('user_id',Auth::user()->id);
        })
        ->where('reply_post_id',null)
        ->orderby('id','desc')
        ->get();
        $filtered_posts=$posts->filter(function($post){
            return (($post['repost_id'] == null) || ($post['repost_id'] != null && $post['user_id'] != Auth::user()->id && $post['repost']['user']['follow'] == false && $post['repost']['user']['id'] != Auth::user()->id ));
        })->values();
        //リツイートじゃない or リツイートかつ自分のリツイートではないかつリツイート先がフォロワーと自分ではない
        $param=['posts'=>$filtered_posts];
        return view('main.home.top',$param);
    }
}
