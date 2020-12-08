@extends('layouts.base')

@section('pagename')
    フォロー中 ー{{ $user->name }}さん
@endsection

@include('layouts.head')

@include('layouts.header')

@section('content')
    <div id="follow_list">
        <h1>{{ $user->name }}さんがフォロー中</h1>
        @if (count($user->follows)!=0)
            <ul class="users">
                @foreach ($user->follows as $f)
                    <li class="user">
                        <a href="/user/{{ $f->follow->user_name }}">
                            <div class="post_icon">
                                <img src="/storage/user_icons/{{ $f->follow->icon_path }}" alt="">
                            </div>
                            <div class="post_texts">
                                <p class="post_user_name"><b>{{ $f->follow->name }}</b>　{{ "@".$f->follow->user_name }}</p>
                                <p class="post_contents">{{ $f->follow->self_introduction }}</p>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p>{{ $user->name }}さんがフォローしている人はいません。</p>
        @endif
    </div>
@endsection
@include('layouts.footer')
