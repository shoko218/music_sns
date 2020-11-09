<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;

class RemoveMyMusicController extends Controller
{
    public function __invoke(){
        $user=User::find(Auth::user()->id);
        $user->update(['my_music_track_id'=>null]);
        return 0;
    }
}
