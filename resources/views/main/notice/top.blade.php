@extends('layouts.base')

@section('pagename')
@endsection

@include('layouts.head')

@include('layouts.header')

@section('content')
<section id="show_notices">
    <div id="notices">
        @foreach ($notices as $notice)
            <div class="notice">
                    @switch($notice->action_type)
                        @case(1)
                            <a href="/home/detail/{{ $notice->target_reply_post_id }}#main_post">
                                <p class="notice_title">
                                    <i class="fas fa-reply"></i>
                                    <object><a href="/user/{{ $notice->from_user->user_name }}"><img src="/storage/user_icons/{{ $notice->from_user->icon_path }}" alt=""></a></object>
                                     {{ $notice->from_user->name }}さんがあなたの投稿に返信しました
                                </p>
                                <p class="notice_description">{{ $notice->target_reply_post->contents }}</p>
                            </a>
                            @break
                        @case(2)
                            @if ($notice->target_post_id!=null)
                                <a href="/home/detail/{{ $notice->target_post_id }}#main_post">
                            @else
                                <a href="/playlist/detail/{{ $notice->target_playlist_id }}#main_post">
                            @endif
                                <p class="notice_title">
                                    <i class="fas fa-retweet reposted"></i>
                                    <object><a href="/user/{{ $notice->from_user->user_name }}"><img src="/storage/user_icons/{{ $notice->from_user->icon_path }}" alt=""></a></object>
                                     {{ $notice->from_user->name }}さんがあなたの投稿を拡散しました
                                </p>
                                @if ($notice->target_post_id!=null)
                                    <p class="notice_description">{{ $notice->target_post->contents }}</p>
                                @else
                                    <p class="notice_description">{{ $notice->target_playlist->title }}</p>
                                @endif
                            </a>
                            @break
                        @case(3)
                            @if ($notice->target_post_id!=null)
                                <a href="/home/detail/{{ $notice->target_post_id }}#main_post">
                            @else
                                <a href="/playlist/detail/{{ $notice->target_playlist_id }}#main_post">
                            @endif
                                <p class="notice_title">
                                    <i class="fas fa-heart liked"></i>
                                    <object><a href="/user/{{ $notice->from_user->user_name }}"><img src="/storage/user_icons/{{ $notice->from_user->icon_path }}" alt=""></a></object>
                                     {{ $notice->from_user->name }}さんがあなたの投稿にいいねしました
                                </p>
                                @if ($notice->target_post_id!=null)
                                    <p class="notice_description">{{ $notice->target_post->contents }}</p>
                                @else
                                    <p class="notice_description">{{ $notice->target_playlist->title }}</p>
                                @endif
                            </a>
                            @break
                    @endswitch
            </div>
        @endforeach
    </div>
</section>
@endsection

@include('layouts.footer')
