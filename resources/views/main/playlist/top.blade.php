@extends('layouts.base')

@section('pagename')
プレイリストホーム
@endsection

@include('layouts.head')

@include('layouts.header')

@section('content')
<div id="playlist_home">
    <div class="btns">
        <button onclick="location.href='/playlist/create'">新規プレイリストを作成する</button>
    </div>
    <show-playlists-component :playlists='@json($playlists)' user-id={{ Auth::user()->id }}></show-playlists-component>
    {{-- <section class="playlists">
        @foreach ($playlists as $p)
            <div class="playlist" onclick="location.href='/playlist/detail/{{ $p->id }}'">
                <div class="playlist_icon"><!--アイコン--->
                    <a href="/user/{{ $p->user->user_name }}">
                        <img src="/storage/user_icons/{{ $p->user->icon_path }}" alt="">
                    </a>
                </div>
                <div class="playlist_texts"><!--投稿データ-->
                    <p class="playlist_user_name"><b>{{ $p->user->name }}</b> {{ "@".$p->user->user_name }}</p><!--ユーザー名-->
                    <div class="playlist_info">
                        <div class="playlist_info_img">
                            <img src="/storage/playlist_imgs/noimage.png">
                        </div>
                        <div class="playlist_info_detail">
                            <h2>{{ $p->title }}</h2>
                            <p>{{ $p->description }}</p>
                        </div>
                    </div>
                    <div class="playlist_action_btns">
                        <p><a><i class="fas fa-reply"></i></a></p><!--リプライ(☆未実装)-->
                        <p><a><i class="fas fa-retweet"></i></a></p><!--リツイート(☆未実装)-->
                        <p><a><i class="fas fa-star"></i></a></p><!--お気に入り(☆未実装)-->
                        <p>
                            @if ($p->user->id==Auth::user()->id)
                                <a><i class="fas fa-trash-alt"></i></a>
                            @endif
                        </p><!--投稿削除-->
                    </div>
                </div>
            </div>
        @endforeach
    </section> --}}
</div>
@endsection

@include('layouts.footer')
