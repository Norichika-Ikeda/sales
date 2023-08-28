@extends('layouts.app')

@section('content')
<div class="container">
    <h2>商品情報詳細画面</h2>
    <div class="p-detail">
        {{ Form::open(['url' => 'editForm/' . $details->id]) }}
        @csrf
        <div class="p-detail__form d-flex align-items-center">
            <label for="" class="col-sm-4">ID</label>
            <p class="my-3">{{ $details->id }}</p>
        </div>
        <div class="p-detail__form d-flex align-items-center">
            <label for="" class="col-sm-4">商品画像</label>
            <p class="my-3">
                @if($details->img_path)
                <img src="{{ asset('storage/images/' . $details->img_path) }}">
                @else
                商品画像が登録されていません。
                @endif
            </p>
        </div>
        <div class="p-detail__form d-flex align-items-center">
            <label for="" class=" col-sm-4">商品名</label>
            <p class="my-3">{{ $details->product_name }}</p>
        </div>
        <div class="p-detail__form d-flex align-items-center">
            <label for="" class="col-sm-4">メーカー名</label>
            <p class="my-3">{{ $details->company->company_name }}</p>
        </div>
        <div class="p-detail__form d-flex align-items-center">
            <label for="" class="col-sm-4">価格</label>
            <p class="my-3">￥{{ $details->price }}</p>
        </div>
        <div class="p-detail__form d-flex align-items-center">
            <label for="" class="col-sm-4">在庫数</label>
            <p class="my-3">{{ $details->stock }}</p>
        </div>
        <div class="p-detail__form d-flex align-items-center mb-4">
            <label for="" class="col-sm-4">コメント</label>
            <p class="my-3">{{ $details->comment }}</p>
        </div>
        <div class="btn-toolbar">
            <button type="submit" class="btn btn-warning me-4">編集</button>
            {{ Form::close() }}
            {{ Form::open(['route' => 'list', 'method' => 'get']) }}
            <button type="submit" class="btn btn-info">戻る</button>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
