<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\BaseClass;

class GetUserLikedPostsController extends Controller
{
    public function __invoke(Request $request){
        $user_id=$request->input('user_id');
        if($user_id != null){
            $param = ['posts'=>BaseClass::get_user_liked_posts($user_id)];
        }else{
            $param = null;
        }
        return $param;
    }
}
