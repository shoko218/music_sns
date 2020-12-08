@extends('layouts.base')

@section('pagename')
    フォロワー ー{{ $user->name }}さん
@endsection

@include('layouts.head')

@include('layouts.header')

@section('content')
    <div id="follower_list">
        <h1>{{ $user->name }}さんのフォロワー</h1>
        @if (count($user->followers)!=0)
            <ul class="users">
                @foreach ($user->followers as $f)
                <li class="user">
                    <a href="/user/{{ $f->follower->user_name }}">
                        <img src="/storage/user_icons/{{ $f->follower->icon_path }}" alt="">
                        <div class="user_icon">
                        </div>
                        <div class="user_infos">
                            <p class="user_name"><b>{{ $f->follower->name }}</b>　{{ "@".$f->follower->user_name }}</p>
                            <p class="user_description">{{ $f->follower->self_introduction }}</p>
                        </div>
                    </a>
                </li>
                @endforeach
            </ul>
        @else
            <p>{{ $user->name }}さんのフォロワーはいません。</p>
        @endif
    </div>
@endsection
@include('layouts.footer')
