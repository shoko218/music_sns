<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    protected $fillable = ['user_id','title','description','img_path'];

    public static $rules=array(
        'title' => ['required', 'string', 'max:50'],//プレイリスト名
        'description' => ['required','string', 'max:400'],//プレイリストの説明(最大400字)
        'image'=>['nullable','file','mimes:jpeg,png,jpg','max:10240'],//アイコン
    );

    public function user(){
        return $this->belongsTo('App\Model\User');
    }

    public function playlist_logs(){
        return $this->hasMany('App\Model\PlaylistLog');
    }

}
