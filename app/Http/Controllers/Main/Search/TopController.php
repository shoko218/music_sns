<?php

namespace App\Http\Controllers\Main\Search;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TopController extends Controller
{
    public function __invoke(Request $request){

        return view('main.search.index');
    }
}
