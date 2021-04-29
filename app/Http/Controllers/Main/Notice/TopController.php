<?php

namespace App\Http\Controllers\Main\Notice;

use App\Http\Controllers\Controller;
use App\Model\Notice_log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopController extends Controller
{
    public function __invoke(Request $request){
        $notices=Notice_log::where('to_user_id',Auth::user()->id)->orderBy('id','desc')->get();
        $param=['notices'=>$notices];
        return view('main.notice.top',$param);
    }
}
