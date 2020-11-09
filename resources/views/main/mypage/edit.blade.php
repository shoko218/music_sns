@extends('layouts.base')

@push('js')
    <script src="{{ mix('js/player.js') }}" defer></script>
@endpush

@section('pagename')
    登録内容の変更
@endsection

@include('layouts.head')

@include('layouts.header')

@section('content')
    <section id="edit" class="normal_section center_section">
        <h1>登録内容の変更</h1>
        <form id="edit_profile" method="POST" action="/mypage/edit_process" class="input_form" enctype='multipart/form-data'>
            @csrf
            <ul class="inputs">
                <li class="input_parts">
                    <label for="name">名前(40文字以内)</label>
                    <input id="name" type="text" name="name" @if(old('name')!=null) value="{{ old('name') }}" @else value="{{ $user->name }}" @endif @if ($errors->has('name')) class="input_alert" @endif placeholder="ニックネーム" required>
                    @foreach ($errors->get('name') as $item)
                        <p class="form_alert">{{ $item }}</p>
                    @endforeach
                </li>
                <li class="input_parts">
                    <label for="self_introduction">自己紹介</label>
                    <textarea id="self_introduction" type="text" name="self_introduction" @if(old('self_introduction')!=null) value="{{ old('self_introduction') }}" @else value="{{ $user->self_introduction }}" @endif placeholder="あなたのことを教えてください。" rows="3" @if ($errors->has('self_introduction')) class="input_alert" @endif>{{ old('self_introduction') }}</textarea>
                    @foreach ($errors->get('self_introduction') as $item)
                        <p class="form_alert">{{ $item }}</p>
                    @endforeach
                </li>
                <icon-component :err-msgs='@json($errors->get('image'))' @if($user->icon_path!=null) img-path={{ $user->icon_path }} @endif @if(env('APP_ENV') == 'production' && $user->icon_path!=null) s3-url={{ Storage::disk('s3')->url('user_icons/'.$user->icon_path)}}@endif></icon-component>
            </ul>
        </form>
        <div class="edit_profile_form_parts">
            <music-search-component :music-id='@json($user->my_music_track_id)'></music-search-component>
            <div class="btns">
                <button type="submit" form="edit_profile">登録</button>
            </div>
        </div>
    </section>
@endsection
@include('layouts.footer')
