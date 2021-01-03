<?php

namespace App\Http\Controllers\Main\Playlist;

use App\Http\Controllers\Controller;
use App\Model\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DeletePlaylistProcessController extends Controller
{
    public function __invoke(Request $request){//プレイリストの削除
        if ($request->playlist_id!=null) {//プレイリストidが指定されているか、プレイリストが存在しているものか、ユーザー自身のプレイリストかを確認し、削除
            $playlist=Playlist::find($request->playlist_id);
            if ($playlist!=null&&Gate::allows('edit-and-delete',$playlist->user_id)) {
                $playlist->delete();
                if($request->from_detail==true){
                    session()->flash('suc_msg','削除しました。');
                    return ;
                }
                $param=['deleted_id'=>$request->playlist_id];
            }else{
                $param=['deleted_id'=>-1];
            }
            return $param;
        }
    }
}
