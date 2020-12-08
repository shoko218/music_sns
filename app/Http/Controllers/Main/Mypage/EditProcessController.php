<?php

namespace App\Http\Controllers\Main\Mypage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EditProcessController extends Controller
{
    public function __invoke(Request $request){//自分の登録情報を変更するプロセス
        $this->validate($request,User::$normal_change_rules);//バリデーション通す
        $user=User::find(Auth::user()->id);
        $user->fill($request->except(['icon']))->save();//アイコンだけ保存系統が違うのでアイコン以外を先に保存
        try {
            if($request->icon!=null){
                $file_name = uniqid(rand());
                $path=$request->icon->path();
                $image=\Image::make($path);
                $image->fit(480,480,function($constraint){//写真を圧縮
                    $constraint->upsize();
                });
                if (env('APP_ENV') === 'production') {//本番環境ならS3に保存
                    if($user->icon_path!=null){
                        Storage::disk('s3')->delete('/user_icons/'.$user->icon_path);
                    }
                    Storage::disk('s3')->put('/user_icons/'.$file_name.'.jpg',(string)$image->encode(),'public');
                }else{//開発環境ならローカル内に保存
                    if($user->icon_path!=null){
                        Storage::delete('storage/user_icons/'.$user->icon_path);
                    }
                    $image->save('storage/user_icons/'.$file_name.'.jpg');
                }
                $user->update(['icon_path'=>$file_name.'.jpg']);
            }
        } catch (\Throwable $th) {
            $param=['user'=>$user];
            return redirect('/mypage/edit')->with('err_msg','エラーが発生しました。');
        }
        return redirect('/user/'.$user->user_name.'')->with('suc_msg','変更しました。');
    }
}
