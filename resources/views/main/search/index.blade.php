@extends('layouts.base')

@section('pagename')
    検索する
@endsection

@include('layouts.head')

@include('layouts.header')

@section('content')
    <search-component></search-component>
@endsection

@include('layouts.footer')
