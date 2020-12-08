@extends('layouts.base')

@section('pagename')
    ホーム
@endsection

@include('layouts.head')

@include('layouts.header')

@section('content')
    <home-top-component :posts='@json($posts)' csrf='{{ csrf_token() }}'></home-top-component>
@endsection

@include('layouts.footer')
