<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'product' => 'required|string|max:255',
            'company' => 'required',
            'price' => 'required|integer|max_digits:11',
            'stock' => 'required|integer|max_digits:11',
            'image' => 'file|image|mimes:jpeg,png,jpg',
        ];
    }

    /** 項目名 */
    public function attributes()
    {
        return [
            'product' => '商品名',
            'company' => 'メーカー名',
            'price' => '価格',
            'stock' => '在庫数',
            'image' => '商品画像',
        ];
    }

    /** エラーメッセージ */
    public function messages()
    {
        return [
            'product.required' => ':attributeは必須項目です。',
            'product.string' => ':attributeには、文字を指定してください。',
            'product.max' => ':attributeは、:max文字以下にしてください。',
            'company.required' => ':attributeは必須項目です。',
            'price.required' => ':attributeは必須項目です。',
            'price.integer' => ':attributeには、整数を指定してください。',
            'price.max_digits' => ':attributeは、:max_digits文字以下にしてください。',
            'stock.required' => ':attributeは必須項目です。',
            'stock.integer' => ':attributeには、整数を指定してください。',
            'stock.max_digits' => ':attributeは、:max_digits文字以下にしてください。',
            'image.file' => ':attributeはファイルでなければいけません。',
            'image.image' => ':attributeには、画像を指定してください。',
            'image.mimes' => ':attributeには、:valuesタイプのファイルを指定してください。',
        ];
    }
}
