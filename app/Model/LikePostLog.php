<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LikePostLog extends Model
{
    protected $guarded = ['id','created_at','updated_at'];

    public function posts(){
        return $this->belongsToMany('App\Model\Post');
    }
}
