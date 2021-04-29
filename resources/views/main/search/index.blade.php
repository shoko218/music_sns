@extends('layouts.base')

@section('pagename')
    検索する
@endsection

@include('layouts.head')

@include('layouts.header')

@section('content')
    <search-component :user-id={{ Auth::user()->id }}></search-post-component>
@endsection

@include('layouts.footer')
