<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Model\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SendPostProcessController extends Controller
{
    public function __invoke(Request $request){
        $this->validate($request,Post::$post_rules);
        try {
            $post=new Post;
            $post->user_id=Auth::user()->id;
            $post->fill($request->except('image'))->save();
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
        return redirect('/home')->with('suc_msg','送信しました。');
    }
}
