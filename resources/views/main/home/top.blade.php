@extends('layouts.base')

@section('pagename')
@endsection

@include('layouts.head')

@include('layouts.header')

@section('content')
<div id="create_post">
    <form id="create_post_form" action="/send_post_process" method="post" enctype='multipart/form-data'>
        @csrf
        <p>投稿する</p>
        <textarea name="contents" id="" cols="30" rows="6" placeholder=""></textarea>
        <add-media-to-post-component></add-media-to-post-component>
    </form>
    <div class="btns">
        <button form="create_post_form">送信</button>
    </div>
</div>
<div id="home">
    @foreach ($posts as $post)
    <div class="post">
        <div class="post_icon">
            <img src="/storage/user_icons/{{ $post->user->icon_path }}" alt="">
        </div>
        <div class="post_texts">
            <p class="post_text_name"><b>{{ $post->user->name }}</b>{{ " "."@".$post->user->user_name }}</p>
            <p class="post_text_contents">{{ $post->contents }}</p>
        </div>
        {{-- @if ($post->track_id!=null)
            @include('components.music_box')
        @endif --}}
    </div>
    @endforeach
</div>
@endsection

@include('layouts.footer')
