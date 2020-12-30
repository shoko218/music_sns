<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LikePlaylistLog extends Model
{
    protected $guarded = ['id','created_at','updated_at'];

    public function playlists(){
        return $this->belongsToMany('App\Model\Playlist');
    }
}
