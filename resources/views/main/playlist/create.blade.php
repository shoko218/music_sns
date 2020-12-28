@extends('layouts.base')

@section('pagename')
プレイリストを作成
@endsection

@include('layouts.head')

@include('layouts.header')

@section('content')
<create-playlist-component csrf='{{ csrf_token() }}'></create-playlist-component>
@endsection

@include('layouts.footer')
