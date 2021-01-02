<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Playlist extends Model
{
    protected $fillable = ['user_id','title','description','img_path'];

    protected $appends = array('reposted','repost');

    public static $rules=array(
        'title' => ['required', 'string', 'max:50'],//プレイリスト名
        'description' => ['required','string', 'max:400'],//プレイリストの説明(最大400字)
        'image'=>['nullable','file','mimes:jpeg,png,jpg','max:10240'],//アイコン
    );

    public function getRepostedAttribute(){//自分が拡散したかどうか
        $repost=Playlist::where('repost_id',$this->id)
        ->where('user_id',Auth::user()->id)
        ->first();
        if($repost!=null){
            return true;
        }else{
            return false;
        }
    }

    public function getRepostAttribute(){//拡散している対象のプレイリストを取得
        if($this->repost_id!=null){
            $playlist=Playlist::with('user')
            ->with('like_playlist_logs')
            ->find($this->repost_id);
            return $playlist;
        }else{
            return null;
        }
    }

    public function user(){
        return $this->belongsTo('App\Model\User');
    }

    public function playlist_logs(){
        return $this->hasMany('App\Model\PlaylistLog');
    }

    public function like_playlist_logs(){
        return $this->belongsToMany('App\Model\User','like_playlist_logs','playlist_id','user_id')
        ->wherePivot('user_id', Auth::user()->id);
    }
}
