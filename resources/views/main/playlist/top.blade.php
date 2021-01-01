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
    <show-playlists-component :playlists='@json($playlists)' :user-id='@json(Auth::user()->id)'></show-playlists-component>
</div>
@endsection

@include('layouts.footer')
