@extends('layouts.app')

@section('content')
<div class="container">
    <h2>商品新規登録画面</h2>
    <div class="p-create">
        {{ Form::open(['route' => 'create', 'files' => true]) }}
        @csrf
        <div class="p-create__form form-group row form-group row">
            <label for="" class="col-sm-4 col-form-label">商品名<span>*</span></label>
            <input type=" text" name="product" class="form-control @error('product') is-invalid @enderror" value="{{ old('product') }}" autocomplete="product" autofocus>
            @if($errors->has('product'))
            <p>{{ $errors->first('product') }}</p>
            @endif
        </div>
        <div class="p-create__form form-group row">
            <label for="" class="col-sm-4 col-form-label">メーカー名<span>*</span></label>
            <select name="company" class="form-select @error('company') is-invalid @enderror">
                <option value=""></option>
                @foreach ($companies as $company)
                <option value=" {{ $company->id }}">{{ $company->company_name }}</option>
                @endforeach
            </select>
            @if($errors->has('company'))
            <p>{{ $errors->first('company') }}</p>
            @endif
        </div>
        <div class="p-create__form form-group row">
            <label for="" class="col-sm-4 col-form-label">価格<span>*</span></label>
            <input type="text" name="price" class="form-control @error('price') is-invalid @enderror" value=" {{ old('price') }}" autocomplete="price" autofocus>
            @if($errors->has('price'))
            <p>{{ $errors->first('price') }}</p>
            @endif
        </div>
        <div class="p-create__form form-group row">
            <label for="" class="col-sm-4 col-form-label">在庫数<span>*</span></label>
            <input type="text" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock') }}" autocomplete="stock" autofocus>
            @if($errors->has('stock'))
            <p>{{ $errors->first('stock') }}</p>
            @endif
        </div>
        <div class="p-create__form form-group row">
            <label for="" class="col-sm-4 col-form-label">コメント</label>
            <input type="text" name="comment" class="form-control">
        </div>
        <div class="p-create__form form-group row">
            <label for="" class="col-sm-4 col-form-label">商品画像</label>
            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" value="{{ old('image') }}">
            @if($errors->has('image'))
            <p>{{ $errors->first('image') }}</p>
            @endif
        </div>
        <div class="btn-toolbar">
            <button type="submit" class="btn btn-warning me-4">新規登録</button>
            {{ Form::close() }}
            {{ Form::open(['route' => 'list', 'method' => 'get']) }}
            <button type="submit" class="btn btn-info">戻る</button>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
