<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use App\Http\Requests\ProductsRequest;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    public function showList(Request $request)
    {
        $companies = DB::table('companies')->get();
        $products = Products::with('company:id,company_name')->paginate(5);
        $query = Products::with('company');
        $keyword = $request->input('keyword');
        $company_name = $request->input('company');
        if (!empty($keyword) && !empty($company_name)) {
            if ($request->has('keyword')) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('product_name', 'like', "%{$keyword}%")
                        ->orWhere('price', 'like', "%{$keyword}%")
                        ->orWhere('stock', 'like', "%{$keyword}%")
                        ->orWhere('comment', 'like', "%{$keyword}%");
                });
            }
            if ($request->has('company')) {
                $query->whereHas('company', function ($q) use ($company_name) {
                    $q->where('company_name', $company_name);
                });
            }
        } elseif (!empty($keyword) && empty($company_name)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('product_name', 'like', "%{$keyword}%")
                    ->orWhere('price', 'like', "%{$keyword}%")
                    ->orWhere('stock', 'like', "%{$keyword}%")
                    ->orWhere('comment', 'like', "%{$keyword}%");
            });
        } elseif (empty($keyword) && !empty($company_name)) {
            $query->whereHas('company', function ($q) use ($company_name) {
                $q->where('company_name', $company_name);
            });
        } else {
            $query = Products::with('company:id,company_name');
        }
        $products = $query->paginate(5);
        return view('list', ['products' => $products, 'companies' => $companies]);
    }

    public function createProductForm()
    {
        $companies = DB::table('companies')->get();
        return view('create', ['companies' => $companies]);
    }

    public function createProduct(ProductsRequest $request)
    {
        DB::beginTransaction();
        try {
            $product = new Products();
            $product->registProduct($request);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back();
        }

        return redirect('create');
    }

    public function detailProduct($id)
    {
        $details =
            Products::with('company:id,company_name')->find($id);
        return view('detail', ['details' => $details]);
    }

    public function editProductForm($id)
    {
        $product = Products::find($id);
        $companies = DB::table('companies')->get();
        return view('edit', ['product' => $product, 'companies' => $companies]);
    }

    public function editProduct(ProductsRequest $request)
    {
        DB::beginTransaction();
        try {
            $product = Products::find($request->id);
            $product->updateProduct($request);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back();
        }
        return redirect()->route('editForm', ['id' => $request->id]);
    }

    public function deleteProduct($id)
    {
        $product = new Products();
        $product->deleteProducts($id);
        return redirect()->route('list');
    }
}
