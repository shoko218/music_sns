@extends('layouts.base')

@section('pagename')
@endsection

@include('layouts.head')

@include('layouts.header')

@section('content')
    @if ($repost_user_name==null)
        <show-post-component :post='@json($post)' :user-id='@json(Auth::user()->id)' csrf='{{ csrf_token() }}' :replys='@json($replys)' :before-posts='@json($before_posts)' :create-reply='@json($create_reply)'></show-post-component>
    @else
        <show-post-component :post='@json($post)' :user-id='@json(Auth::user()->id)' :repost-user-name='@json($repost_user_name)' csrf='{{ csrf_token() }}' :replys='@json($replys)' :before-posts='@json($before_posts)' :create-reply='@json($create_reply)'></show-post-component>
     @endif
@endsection

@include('layouts.footer')

