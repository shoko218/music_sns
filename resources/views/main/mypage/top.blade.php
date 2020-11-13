@extends('layouts.base')

@section('pagename')
@endsection

@include('layouts.head')

@include('layouts.header')

@section('content')
<section id="mypage">
    <div class="profile_infos">
        @if($user->icon_path!=null)
            <p><img src="/storage/user_icons/{{ $user->icon_path }}" class="user_detail_icon"></p>
        @else
            <p><img src="/image/somethings/noimage_user.jpg" class="user_detail_icon"></p>
        @endif
        <div class="profile_text_infos">
            <h1>{{ $user->name }}</h1>
            <h2>{{ "@".$user->user_name }}</h2>
            <h3>{{ $user->self_introduction }}</h3>
        </div>
        @if ($user->my_music_track_id)
        <div class="my_music_box">
            <p class="play_btn"><i class="far fa-play-circle"></i></p>
            <div class="my_music_infos">
                <p><img src="{{ $music_info['artwork_url'] }}" alt="" class="my_music_artwork"></p>
                <div class="my_music_text_infos">
                    <p class="my_music_title">{{ $music_info['title'] }}</p>
                    <p class="my_music_artist">{{ $music_info['artist'] }}</p>
                    <p class="my_music_itunes_url"><a href="{{ $music_info['itunes_url'] }}">iTunesでダウンロード</a></p>
                </div>
            </div>
            <audio class="audio" src="{{ $music_info['music_url'] }}" preload="auto"></audio>
        </div>
        @else
            <p class="not_set_my_music_text">まだイチオシ曲が設定されていません。<br><a href="http://music_sns.com/mypage/edit#my_music_parts">イチオシ曲を設定する</a></p>
        @endif
        <div class="btns">
            <button class="reverse_btn" onclick="location.href='/mypage/edit'">プロフィールを編集する</button>
        </div>
    </div>
</section>
@endsection
@include('layouts.footer')
