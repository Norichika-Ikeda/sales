@extends('layouts.app')

@section('content')
<div class="container">
    <h1>ユーザーログイン画面</h1>
    <div class="p-login">
        {{ Form::open(['url' => '/login']) }}
        @csrf
        <div class="mb-3 ">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="アドレス" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @if($errors->has('email'))
            <p>{{ $errors->first('email') }}</p>
            @endif
        </div>
        <div class="mb-3">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="パスワード" required autocomplete="current-password">
            @if($errors->has('password'))
            <p>{{ $errors->first('password') }}</p>
            @endif
        </div>
        <div class="btn-toolbar flex-row-reverse">
            <button type="submit" class="btn btn-info btn-lg rounded-pill">ログイン</button>
            {{ Form::close() }}
            {{ Form::open(['route' => 'register', 'method' => 'get']) }}
            <button type="submit" class="btn btn-warning btn-lg rounded-pill">新規登録</button>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
