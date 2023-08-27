@extends('layouts.app')

@section('content')
<div class="container">
    <h1>ユーザー新規登録画面</h1>
    <div class="p-register">
        {{ Form::open(['route' => 'register']) }}
        @csrf
        <div class="mb-3">
            <input id="name" type="text" class="form-control @error('user_name') is-invalid @enderror" name="user_name" placeholder="ユーザー名" value="{{ old('user_name') }}" required autocomplete="user_name" autofocus>
            @if($errors->has('user_name'))
            <p>{{ $errors->first('user_name') }}</p>
            @endif
        </div>
        <div class="mb-3">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="アドレス" value="{{ old('email') }}" required autocomplete="email">
            @if($errors->has('email'))
            <p>{{ $errors->first('email') }}</p>
            @endif
        </div>
        <div class="mb-3">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="パスワード" required autocomplete="new-password">
            @if($errors->has('password'))
            <p>{{ $errors->first('password') }}</p>
            @endif
        </div>
        <div class="mb-3">
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="パスワード（確認用）" required autocomplete="new-password">
        </div>
        <div class="p-btn btn-toolbar flex-row">
            <div class="col-xs-4">
                <button type="submit" class="btn btn-warning btn-lg rounded-pill">新規登録</button>
            </div>
            {{ Form::close() }}
            {{ Form::open(['url' => '/login', 'method' => 'get']) }}
            <div class="col-xs-4">
                <button type="submit" class="btn btn-info btn-lg rounded-pill">戻る</button>
            </div>
        </div>
    </div>
    @endsection
