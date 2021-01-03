<?php

namespace App\Http\Controllers\Main\Home;

use App\Http\Controllers\Controller;
use App\Model\Post;
use Illuminate\Http\Request;

class GetRepliesChainController extends Controller
{
    public function __invoke(Request $request){
        $replies_chain=collect();
        if($request->post_id!=null){
            $p=Post::with('user')
            ->with('like_post_logs')
            ->where('reply_post_id',$request->post_id)
            ->first();
            if($p!=null){
                $replies_chain->push($p);
                while(1){
                    $p=Post::with('user')
                    ->with('like_post_logs')
                    ->where('reply_post_id',$replies_chain->last()->id)
                    ->first();
                    if($p == null)break;
                    $replies_chain->push($p);
                }
            }
        }
        $params=['replies_chain'=>$replies_chain];
        return $params;
    }
}
