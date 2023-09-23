<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Products;

class Sales extends Model
{
    use HasFactory;

    public function Product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    public function addSales($data)
    {
        $val = Products::with('sales')->where('products.id', $data->id)->value('stock');
        if ($val !== 0) {
            DB::table('sales')->insert([
                'product_id' => $data->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('products')
                ->leftjoin('sales', 'products.id', '=', 'sales.product_id')
                ->where('products.id', '=', $data->id)
                ->decrement('stock', 1);
        } else {
            return response()->json('在庫がありません', 422, ['Content-Type' => 'application/json'], JSON_UNESCAPED_UNICODE);
        }
    }
}
