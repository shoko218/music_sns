@extends('layouts.base')

@section('pagename')
    ホーム
@endsection

@include('layouts.head')

@include('layouts.header')

@section('content')
    <home-top-component :posts='@json($posts)' csrf='{{ csrf_token() }}' :user-id='@json(Auth::user()->id)'></home-top-component>
@endsection

@include('layouts.footer')
