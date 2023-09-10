<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Companies;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;

class Products extends Model
{
    use HasFactory, Sortable;

    protected $guarded = [];
    public $sortable = ['id', 'img_path', 'product_name', 'price', 'stock', 'company_name'];

    public function company()
    {
        return $this->belongsTo(Companies::class, 'company_id');
    }

    public function companyNameSortable($query, $direction)
    {
        return $query->leftjoin('companies', 'products.company_id', '=', 'companies.id')
            ->select('products.*')
            ->orderBy('companies.company_name', $direction);
    }

    public function registProduct($data)
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

    public function updateProduct($data)
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
}
