<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{

    protected $fillable = [
        'contents',
        'image',
        'music_track_id',
        'music_title',
        'music_artist',
        'music_artwork',
        'music_url',
        'music_itunes_url',
        'repost_id',
        'reply_post_id',
    ];

    protected $appends = array('reposted','repost');

    public static $post_rules=array(
        'contents'=>['required', 'string', 'max:200'],
        'image'=>['nullable','file','mimes:jpeg,png,jpg','max:10240'],
        'track_id'=>['nullable','integer'],
        'reply_post_id'=>['nullable','integer']
    );
    public static $repost_rules=array(
        'repost_id'=>['required','integer']
    );

    public function getRepostedAttribute(){
        $repost=Post::where('repost_id',$this->id)
        ->where('user_id',Auth::user()->id)
        ->first();
        if($repost!=null){
            return true;
        }else{
            return false;
        }
    }

    public function getRepostAttribute(){
        if($this->repost_id!=null){
            $post=Post::with('user')
            ->with('like_post_logs')
            ->find($this->repost_id);
            return $post;
        }else{
            return null;
        }
    }

    public function user(){
        return $this->belongsTo('App\Model\User');
    }

    public function like_post_logs(){
        return $this->belongsToMany('App\Model\User','like_post_logs','post_id','user_id')
        ->wherePivot('user_id', Auth::user()->id);
    }
}
