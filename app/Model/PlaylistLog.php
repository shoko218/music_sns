<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PlaylistLog extends Model
{
    protected $guarded = ['id','created_at','updated_at'];


    public function playlist(){
        return $this->belongsTo('App\Model\Playlist');
    }
}
