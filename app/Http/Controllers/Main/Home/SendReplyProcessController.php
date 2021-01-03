<?php

namespace App\Http\Controllers\Main\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\BaseClass;
use App\Model\Post;
use Illuminate\Support\Facades\Auth;

class SendReplyProcessController extends Controller
{
    public function __invoke(Request $request){
        $this->validate($request,Post::$post_rules);
        if(Post::find($request->reply_post_id)!=null){
            try {
                $post=new Post;
                $post->user_id=Auth::user()->id;
                $post->fill($request->except('image'))->save();
                if($post->music_track_id!=null){
                    $music_info=BaseClass::getMusic($post->music_track_id);
                    $post->update([
                        'music_title'=>$music_info['title'],
                        'music_artist'=>$music_info['artist'],
                        'music_artwork'=>$music_info['artwork_url'],
                        'music_url'=>$music_info['music_url'],
                        'music_itunes_url'=>$music_info['itunes_url'],
                    ]);
                }
                if($request->file('image')!=null){
                    $file_name = uniqid(rand());
                    $path=$request->image->path();
                    $image=\Image::make($path);
                    $image->resize(600,null,function($constraint){//横幅最大600px、縦横比はそのままでリサイズ
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $post->img_path=$file_name.'.jpg';
                    $post->save();
                    if (env('APP_ENV') === 'production') {//本番環境
                        // Storage::disk('s3')->put('/lover_imgs/'.$file_name.'.jpg',(string)$image->encode(),'public');///未定
                    }else{//開発環境
                        $image->save('storage/post_imgs/'.$file_name.'.jpg');
                    }
                }
            } catch (\Throwable $th) {
                return redirect('/home')->with('err_msg','エラーが発生しました。');
            }
        }else{
            return redirect('/home')->with('err_msg','エラーが発生しました。');
        }
        return redirect('/home/detail/'.$request->reply_post_id.'#main_post')->with('suc_msg','送信しました。');
    }
}
