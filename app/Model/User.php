<?php

namespace App\Model;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'user_name',
        'self_introduction',
        'email',
        'password',
        'icon_path',
        'my_music_track_id',
        'my_music_title',
        'my_music_artist',
        'my_music_artwork',
        'my_music_url',
        'my_music_itunes_url',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static $normal_change_rules=array(
        'name' => ['required', 'string', 'max:40'],//名前
        'self_introduction' => ['nullable','string', 'max:200'],//自己紹介文(最大200字)
        'my_music_track_id' => ['nullable','string'],//イチオシ音楽
        'icon'=>['nullable','file','mimes:jpeg,png,jpg','max:10240'],//アイコン
    );

    protected $appends = array('follow','followed');

    public function getFollowAttribute(){
        if(Fflog::select('*')->where('to_user_id','=',$this->id)->where('from_user_id','=',Auth::user()->id)->first()!=null){
            return true;
        }else{
            return false;
        }
    }

    public function getFollowedAttribute(){
        if (Fflog::select('*')->where('from_user_id', '=', $this->id)->where('to_user_id', '=', Auth::user()->id)->first()!=null) {
            return true;
        }else{
            return false;
        }
    }

    public function posts(){
        return $this->hasMany('App\Model\Post');
    }

    public function follows(){
        return $this->hasMany('App\Model\Fflog','from_user_id');
    }

    public function followers(){
        return $this->hasMany('App\Model\Fflog','to_user_id');
    }

    public function playlists(){
        return $this->hasMany('App\Model\Playlist');
    }

    public function notice_logs(){
        return $this->hasMany('App\Model\Notice_log','to_user_id');
    }
}
