@extends('layouts.app')

@section('content')
<div class="container">
    <h2>商品情報編集画面</h2>
    <div class="p-edit">
        {{ Form::open(['route' => 'edit', 'files' => true]) }}
        @method('PATCH')
        @csrf
        <div class="p-edit__form d-flex align-items-center mb-4">
            <label for="" class="col-sm-4">ID</label>
            <input type="hidden" name="id" class="p-edit__id--box" value="{{ $product->id }}">
            <p class="my-0">{{ $product->id }}</p>
        </div>
        <div class="p-edit__form form-group row mb-4">
            <label for="" class="col-sm-4 col-form-label">商品名<span>*</span></label>
            <input type="text" name="product" class="form-control w-75 @error('product') is-invalid @enderror" value="{{ $product->product_name }}" autocomplete="product" autofocus>
            @if($errors->has('product'))
            <p>{{ $errors->first('product') }}</p>
            @endif
        </div>
        <div class="p-edit__form form-group row mb-4">
            <label for="" class="col-sm-4 col-form-label">メーカー名<span>*</span></label>
            <select name="company" class="form-select w-75 @error('company') is-invalid @enderror">
                <option value=" {{ $product->company->id }}" selected hidden>{{ $product->company->company_name }}</option>
                @foreach ($companies as $company)
                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                @endforeach
            </select>
            @if($errors->has('company'))
            <p>{{ $errors->first('company') }}</p>
            @endif
        </div>
        <div class="p-edit__form form-group row mb-4">
            <label for="" class="col-sm-4 col-form-label">価格<span>*</span></label>
            <input type="text" name="price" class="form-control w-75 @error('price') is-invalid @enderror" value="{{ $product->price }}" autocomplete="price" autofocus>
            @if($errors->has('price'))
            <p>{{ $errors->first('price') }}</p>
            @endif
        </div>
        <div class="p-edit__form form-group row mb-4">
            <label for="" class="col-sm-4 col-form-label">在庫数<span>*</span></label>
            <input type="text" name="stock" class="form-control w-75 @error('stock') is-invalid @enderror" value="{{ $product->stock }}" autocomplete="stock" autofocus>
            @if($errors->has('stock'))
            <p>{{ $errors->first('stock') }}</p>
            @endif
        </div>
        <div class="p-edit__form form-group row mb-4">
            <label for="" class="col-sm-4 col-form-label">コメント</label>
            <textarea name="comment" class="form-control w-75" rows="3">{{ $product->comment }}</textarea>
        </div>
        <div class="p-edit__form form-group row mb-5">
            <label for="" class="col-sm-4 col-form-label">商品画像</label>
            <input type="file" name="image" class="form-control w-75 @error('image') is-invalid @enderror">
            @if($product->img_path)
            <img src="{{ asset('storage/images/' . $product->img_path) }}">
            @endif
            @if($errors->has('image'))
            <p>{{ $errors->first('image') }}</p>
            @endif
        </div>
        <div class="btn-toolbar">
            <button type="submit" class="btn btn-warning me-4">更新</button>
            {{ Form::close() }}
            {{ Form::open(['url' => 'detail/' .$product->id, 'method' => 'GET']) }}
            <button type="submit" class="btn btn-info">戻る</button>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
