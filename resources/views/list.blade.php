@extends('layouts.app')

@section('content')
<div class="container">
    <h2>商品一覧画面</h2>
    <div class="row p-search">
        <div class="col-8 float-start">
            {{ Form::input('text', 'keyword', null, ['id' => 'keyword', 'class' => 'form-control input-group-prepend', 'placeholder' => '検索キーワード']) }}
        </div>
        <div class="col-4 float-start form-group">
            <select name="company" id="company" class="form-select">
                <option value="" disabled selected style=" display:none;">メーカー名</option>
                @foreach ($companies as $company)
                <option value="{{ $company->company_name }}">{{ $company->company_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mt-4 d-flex align-items-center">
            <div class=" col-2 float-start form-group">
                {{ Form::input('number', 'lower_price', null, ['id' => 'lower_price', 'class' => 'form-control input-group-prepend', 'placeholder' => '下限価格']) }}
            </div>
            <p class="w-auto mx-3 mb-0 align-items-center text-center">~</p>
            <div class="col-2 me-3 float-start form-group">
                {{ Form::input('number', 'upper_price', null, ['id' => 'upper_price', 'class' => 'form-control input-group-prepend', 'placeholder' => '上限価格']) }}
            </div>
            <div class="col-2 ms-3 float-start form-group">
                {{ Form::input('number', 'lower_stock', null, ['id' => 'lower_stock', 'class' => 'form-control input-group-prepend', 'placeholder' => '下限在庫数']) }}
            </div>
            <p class="w-auto mx-3 mb-0 align-items-center text-center">~</p>
            <div class="col-2 float-start form-group">
                {{ Form::input('number', 'upper_stock', null, ['id' => 'upper_stock', 'class' => 'form-control input-group-prepend', 'placeholder' => '上限在庫数']) }}
            </div>
            <div class="col-2 text-end ml-auto p-search__box">
                <button type="button" class="btn btn-secondary px-4 p-search__box--btn"></i>
                    検索
                </button>
            </div>
        </div>
    </div>
    <div class="p-list">
        <table class="table table-striped table-bordered text-center">
            <thead>
                <tr>
                    <th>@sortablelink('id', 'ID')</th>
                    <th>@sortablelink('img_path', '商品画像')</th>
                    <th>@sortablelink('product_name', '商品名')</th>
                    <th>@sortablelink('price', '価格')</th>
                    <th>@sortablelink('stock', '在庫数')</th>
                    <th>@sortablelink('company_name', 'メーカー名')</th>
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
                    {{ Form::open(['url' => 'detail/' .$product->id, 'method' => 'GET']) }}
                    @csrf
                    <td class="p-list__detail"><button type="submit" class="btn btn-info">詳細</button></td>
                    {{ Form::close() }}
                    <td class="p-list__remove"><button type="submit" id="{{ $product->id }}" class="btn btn-danger">削除</button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $products->appends(request()->query())->links() }}
    </div>
</div>
@push('script')
<script type="module" src="{{ asset('../resources/js/app.js') }}"></script>
@endpush
@endsection
