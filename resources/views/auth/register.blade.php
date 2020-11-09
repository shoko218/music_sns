@extends('layouts.base')

@section('pagename')
    新規登録
@endsection

@include('layouts.head')

@include('layouts.header')

@section('content')
    <section id="register" class="normal_section center_section">
        <h1>新規登録</h1>
        <form method="POST" action="{{ route('register') }}" class="input_form" enctype='multipart/form-data'>
            @csrf
            <ul class="inputs">
                <li class="input_parts">
                    <label for="email">メールアドレス</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="example@mail.com" @if ($errors->has('email')) class="input_alert" @endif required autocomplete="email">
                    @foreach ($errors->get('email') as $item)
                    <p class="form_alert">{{ $item }}</p>
                    @endforeach
                </li>
                <li class="input_parts">
                    <label for="password">パスワード(8文字以上の英数字)</label>
                    <input id="password" type="password" name="password" placeholder="password" @if ($errors->has('password')) class="input_alert" @endif required>
                    @foreach ($errors->get('password') as $item)
                    <p class="form_alert">{{ $item }}</p>
                    @endforeach
                </li>
                <li class="input_parts">
                    <label for="password-confirm">確認用パスワード</label>
                    <input id="password-confirm" type="password" name="password_confirmation" placeholder="password" required>
                </li>
                <li class="input_parts">
                    <label for="name">名前</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" @if ($errors->has('name')) class="input_alert" @endif placeholder="ニックネーム" required>
                    @foreach ($errors->get('name') as $item)
                        <p class="form_alert">{{ $item }}</p>
                    @endforeach
                </li>
                <li class="input_parts">
                    <label for="user_name">ユーザー名(15文字以内の)</label>
                    <input id="user_name" type="text" name="user_name" value="{{ old('user_name') }}" @if ($errors->has('user_name')) class="input_alert" @endif placeholder="xxxxxxx_123" required>
                    @foreach ($errors->get('user_name') as $item)
                        <p class="form_alert">{{ $item }}</p>
                    @endforeach
                </li>
                <li class="input_parts">
                    <label for="self_introduction">自己紹介</label>
                    <textarea id="self_introduction" type="text" name="self_introduction" value="{{ old('self_introduction') }}" placeholder="あなたのことを教えてください。" rows="3" @if ($errors->has('self_introduction')) class="input_alert" @endif>{{ old('self_introduction') }}</textarea>
                    @foreach ($errors->get('self_introduction') as $item)
                        <p class="form_alert">{{ $item }}</p>
                    @endforeach
                </li>
                <icon-component :err-msgs='@json($errors->get('image'))'></icon-component>
                <li>
                    <label class="check">
                    <input type="checkbox" name="agree" class="checkbtn" {{ is_array(old("agree")) && in_array("1", old("agree"), true)? 'checked="checked"' : ''}} required>
                        <a href="rules" target="_blank">利用規約</a>に同意します。
                    </label>
                </li>
            </ul>
            <div class="btns">
                <button type="submit">登録</button>
            </div>
        </form>
    </section>
@endsection
@include('layouts.footer')
