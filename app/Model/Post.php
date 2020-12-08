<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

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

    public static $post_rules=array(
        'contents'=>['required', 'string', 'max:200'],
        'image'=>['nullable','file','mimes:jpeg,png,jpg','max:10240'],
        'track_id'=>['nullable','integer'],
        'reply_post_id'=>['nullable','integer']
    );
    public static $repost_rules=array(
        'repost_id'=>['required','integer']
    );
    public function user(){
        return $this->belongsTo('App\Model\User');
    }
}
