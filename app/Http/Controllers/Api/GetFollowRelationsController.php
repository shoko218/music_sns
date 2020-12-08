<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Fflog;
use Illuminate\Http\Request;

class GetFollowRelationsController extends Controller
{
    public function __invoke(Request $request){
        $follow_num=Fflog::where('from_user_id',$request->user_id)->count();
        $follower_num=Fflog::where('to_user_id',$request->user_id)->count();
        $param=['follow_num'=>$follow_num,'follower_num'=>$follower_num];
        return $param;
    }
}
