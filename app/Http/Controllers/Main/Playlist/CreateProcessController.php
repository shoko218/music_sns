<?php

namespace App\Http\Controllers\Main\Playlist;

use App\Http\Controllers\Controller;
use App\Library\BaseClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\Playlist;
use App\Model\PlaylistLog;
use Illuminate\Support\Facades\Storage;

class CreateProcessController extends Controller
{
    public function __invoke(Request $request){
        $this->validate($request,Playlist::$rules);
        $playlist=Playlist::create(['user_id' => Auth::user()->id,'title' => $request->title,'description' => $request->description]);
        foreach ($request->track_ids as $t) {
            $infos=BaseClass::getMusic($t);
            PlaylistLog::create(['playlist_id' => $playlist->id,'music_track_id' => $t,'music_title' => $infos['title'],'music_artist' => $infos['artist'],'music_artwork' => $infos['artwork_url'],'music_itunes_url' => $infos['itunes_url'],'music_url' => $infos['music_url']]);
        }
        if($request->image!=null){
            $file_name = uniqid(rand());
            $path=$request->image->path();
            $image=\Image::make($path);
            $image->fit(480,480,function($constraint){//写真を圧縮
                $constraint->upsize();
            });
            if (env('APP_ENV') === 'production') {//本番環境ならS3に保存
                Storage::disk('s3')->put('/playlist_imgs/'.$file_name.'.jpg',(string)$image->encode(),'public');
            }else{//開発環境ならローカル内に保存
                $image->save('storage/playlist_imgs/'.$file_name.'.jpg');
            }
            $playlist->update(['img_path'=>$file_name.'.jpg']);
            print_r($playlist);
        }
        return redirect('/playlist')->with('suc_msg','プレイリストを投稿しました。');
    }
}
