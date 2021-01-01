@extends('layouts.base')

@section('pagename')
    {{ $user->name }}さん
@endsection

@include('layouts.head')

@include('layouts.header')

@section('content')
    <user-detail-component :user='@json($user)' :posts='@json($posts)' :liked-posts='@json($liked_posts)' :playlists='@json($playlists)' :liked-playlists='@json($liked_playlists)' :is-follow='@json($is_follow)' :my-id='@json(Auth::user()->id)'></user-detail-component>
@endsection
@include('layouts.footer')
