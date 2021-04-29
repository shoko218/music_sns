<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Notice_log extends Model
{
    protected $guarded = ['id','created_at','updated_at'];

    protected $appends = array('from_user','target_reply_post','target_post','target_playlist');

    protected $attributes = ['read' => false];

    public function getFromUserAttribute(){//拡散している対象のプレイリストを取得
        return User::find($this->from_user_id);
    }

    public function getTargetReplyPostAttribute(){//拡散している対象のプレイリストを取得
        if($this->target_reply_post_id != null){
            return Post::with('user')
            ->with('like_post_logs')
            ->find($this->target_reply_post_id);
        }else{
            return null;
        }
    }

    public function getTargetPostAttribute(){//拡散している対象のプレイリストを取得
        if($this->target_post_id != null){
            return Post::with('user')
            ->with('like_post_logs')
            ->find($this->target_post_id);
        }else{
            return null;
        }
    }

    public function getTargetPlaylistAttribute(){//拡散している対象のプレイリストを取得
        if($this->target_playlist_id != null){
            return Playlist::with('user')
            ->with('like_playlist_logs')
            ->find($this->target_playlist_id);
        }else{
            return null;
        }
    }
}
