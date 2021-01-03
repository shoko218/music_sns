@extends('layouts.base')

@section('pagename')
@endsection

@include('layouts.head')

@include('layouts.header')

@section('content')
    <div class="post_detail">
        @if ($repost_user_name==null)
            <show-post-component :post='@json($post)' :user-id='@json(Auth::user()->id)'></show-post-component>
        @else
            <show-post-component :post='@json($post)' :user-id='@json(Auth::user()->id)' :repost-user-name='@json($repost_user_name)'></show-post-component>
        @endif
    </div>
@endsection

@include('layouts.footer')

