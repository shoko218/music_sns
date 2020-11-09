<?php

namespace App\Http\Controllers\Main\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TopController extends Controller
{
    public function __invoke(Request $request){
        return view('main.notification.top');
    }
}
