@extends('layouts.base')

@section('pagename')
@endsection

{{-- @push('js')
    <script src="{{ mix('js/player.js') }}" defer></script>
@endpush --}}

@include('layouts.head')

@include('layouts.header')

@section('content')
    <user-detail-component :user='@json($user)' :posts='@json($posts)'></user-detail-component>
@endsection
@include('layouts.footer')
