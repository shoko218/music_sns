@extends('layouts.base')

@section('pagename')
@endsection

@include('layouts.head')

@include('layouts.header')

@section('content')
<form action="/send_post_process" method="post"></form>
@endsection

@include('layouts.footer')
