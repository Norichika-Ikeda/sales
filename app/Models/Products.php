<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Companies;
use Illuminate\Support\Facades\DB;

class Products extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Companies::class, 'company_id');
    }

    public function registProducts($data)
    {
        $file_path = $data->file('image');
        if (isset($file_path)) {
            $file_path = $data->file('image')->getClientOriginalName();
            $data->file('image')->storeAs('public/images', $file_path);
            DB::table('products')->insert([
                'product_name' => $data->product,
                'company_id' => $data->company,
                'price' => $data->price,
                'stock' => $data->stock,
                'comment' => $data->comment,
                'img_path' => $file_path,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            DB::table('products')->insert([
                'product_name' => $data->product,
                'company_id' => $data->company,
                'price' => $data->price,
                'stock' => $data->stock,
                'comment' => $data->comment,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function updateProducts($data)
    {
        $file_path = $data->file('image');
        $product_id = Products::find($data->id);
        if (isset($file_path)) {
            $file_path = $data->file('image')->getClientOriginalName();
            $data->file('image')->storeAs('public/images', $file_path);
            $product_id->update([
                'product_name' => $data->product,
                'company_id' => $data->company,
                'price' => $data->price,
                'stock' => $data->stock,
                'comment' => $data->comment,
                'img_path' => $file_path,
            ]);
        } else {
            $product_id->update([
                'product_name' => $data->product,
                'company_id' => $data->company,
                'price' => $data->price,
                'stock' => $data->stock,
                'comment' => $data->comment,
            ]);
        }
    }

    public function deleteProducts($id)
    {
        return $this->destroy($id);
    }
}
