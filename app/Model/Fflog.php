<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Fflog extends Model
{
    protected $fillable = ['from_user_id','to_user_id'];

    public function follow(){
        return $this->belongsTo('App\Model\User','to_user_id');
    }

    public function follower(){
        return $this->belongsTo('App\Model\User','from_user_id');
    }
}
