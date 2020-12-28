<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Model\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeletePostProcessController extends Controller
{
    public function __invoke(Request $request){//投稿の削除
        if ($request->post_id!=null) {//投稿idが指定されているか、投稿が存在しているものか、ユーザー自身の投稿かを確認し、削除
            $post=Post::find($request->post_id);
            if ($post!=null&&$post->user_id==Auth::user()->id) {
                $post->delete();
                $param=['deleted_id'=>$request->post_id];
            }else{
                $param=['deleted_id'=>-1];
            }
            return $param;
        }
    }
}
