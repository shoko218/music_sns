<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'user_name','self_introduction','email', 'password','icon_path','my_music_track_id'
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

    public function posts(){
        return $this->hasMany('App\Model\Post');
    }
}
