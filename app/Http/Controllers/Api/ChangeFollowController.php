<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Fflog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChangeFollowController extends Controller
{
    public function __invoke(Request $request){
        $follow_log=Fflog::select('*')->where('from_user_id','=',Auth::user()->id)->where('to_user_id','=',$request->to_user_id)->first();
        if($follow_log!=null){//フォロー中なら
            $follow_log->delete();
            $is_follow=false;
        }else{//フォローしてなければ
            Fflog::create(['from_user_id' => Auth::user()->id,'to_user_id' => $request->to_user_id]);
            $is_follow=true;
        }
        $param=['is_follow'=>$is_follow];
        return $param;
    }
}
