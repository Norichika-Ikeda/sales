@extends('layouts.app')

@section('content')
<div class="container">
    <h2>商品一覧画面</h2>
    <div class="row p-search">
        {{ Form::open(['route' => 'list', 'method' => 'GET']) }}
        @csrf
        <div class="col-6 float-start">
            {{ Form::input('text', 'keyword', null, ['class' => 'form-control input-group-prepend', 'placeholder' => '検索キーワード']) }}
        </div>
        <div class="col-3 float-start form-group">
            <select name="company" class="form-select">
                <option value="" disabled selected style=" display:none;">メーカー名</option>
                @foreach ($companies as $company)
                <option value="{{ $company->company_name }}">{{ $company->company_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="p-search__box">
            <button type="submit" class="btn btn-secondary p-search__box--btn"></i>
                検索
            </button>
        </div>
        {{ Form::close() }}
    </div>
    <div class="p-list">
        <table class="table table-striped table-bordered text-center">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>商品画像</th>
                    <th>商品名</th>
                    <th>価格</th>
                    <th>在庫数</th>
                    <th>メーカー名</th>
                    <th colspan="2"><a href="create" class="btn btn-warning">新規登録</a></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>
                        @if($product->img_path)
                        <img src="{{ asset('storage/images/' . $product->img_path) }}">
                        @else
                        <p class="m-0">商品画像</p>
                        @endif
                    </td>
                    <td>{{ $product->product_name }}</td>
                    <td>￥{{ $product->price }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->company->company_name }}</td>
                    {{ Form::open(['url' => 'detail/' .$product->id]) }}
                    @csrf
                    <td class="p-list__detail"><button type="submit" class="btn btn-info">詳細</button></td>
                    {{ Form::close() }}
                    {{ Form::open(['url' => 'delete/' .$product->id]) }}
                    @csrf
                    <td class="p-list__remove"><button type="submit" class="btn btn-danger" onclick="return confirm('本当に削除しますか？')">削除</button></td>
                    {{ Form::close() }}
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $products->appends(request()->query())->links() }}
    </div>
</div>
@endsection
