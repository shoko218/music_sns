<?php

namespace App\Http\Controllers\Main\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\BaseClass;
use App\Model\Post;
use Illuminate\Support\Facades\Auth;
use App\Model\Notice_log;

class SendReplyProcessController extends Controller
{
    public function __invoke(Request $request){
        $this->validate($request,Post::$post_rules);
        if(Post::find($request->reply_post_id)!=null){
            try {
                $new_post=new Post;
                $new_post->user_id=Auth::user()->id;
                $new_post->fill($request->except('image'))->save();
                if($new_post->music_track_id!=null){
                    $music_info=BaseClass::getMusic($new_post->music_track_id);
                    $new_post->update([
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
                    $new_post->img_path=$file_name.'.jpg';
                    $new_post->save();
                    if (env('APP_ENV') === 'production') {//本番環境
                        // Storage::disk('s3')->put('/lover_imgs/'.$file_name.'.jpg',(string)$image->encode(),'public');///未定
                    }else{//開発環境
                        $image->save('storage/post_imgs/'.$file_name.'.jpg');
                    }
                }

                $post=Post::with('user')
                ->with('like_post_logs')
                ->find($request->reply_post_id);

                if(Auth::user()->id != $post['user_id'] ){
                    Notice_log::create(['from_user_id' => Auth::user()->id , 'to_user_id'=>$post['user_id'] , 'action_type'=> 1 , 'target_reply_post_id' => $new_post['id']]);
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
