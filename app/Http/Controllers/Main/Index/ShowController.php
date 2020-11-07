<?php

namespace App\Http\Controllers\Main\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function __invoke(){
        return view('main.index');
    }
}
