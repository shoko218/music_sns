<?php

namespace App\Http\Controllers\dm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TopController extends Controller
{
    public function __invoke(){
        return view('main.dm.top');
    }
}
