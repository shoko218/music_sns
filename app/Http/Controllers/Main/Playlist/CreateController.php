<?php

namespace App\Http\Controllers\Main\Playlist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    public function __invoke(){
        return view('main.playlist.create');
    }
}
