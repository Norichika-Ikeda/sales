<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use App\Http\Requests\ProductsRequest;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    public function showList()
    {
        $companies = DB::table('companies')->get();
        $query = Products::with('company');
        $products = $query->sortable()->orderByDesc('id')->paginate(5);
        return view('list', ['products' => $products, 'companies' => $companies]);
    }

    public function searchList(Request $request)
    {
        $query = Products::with('company:id,company_name');
        $search_keyword = $request->keyword;
        $search_company = $request->company;
        if (!empty($search_keyword) && !empty($search_company)) {
            if ($request->has('keyword')) {
                $query->where(function ($q) use ($search_keyword) {
                    $q->where('product_name', 'like', "%{$search_keyword}%")
                        ->orWhere('price', 'like', "%{$search_keyword}%")
                        ->orWhere('stock', 'like', "%{$search_keyword}%")
                        ->orWhere('comment', 'like', "%{$search_keyword}%");
                });
            }
            if ($request->has('company')) {
                $query->whereHas('company', function ($q) use ($search_company) {
                    $q->where('company_name', $search_company);
                });
            }
        } elseif (!empty($search_keyword) && empty($search_company)) {
            $query->where(function ($q) use ($search_keyword) {
                $q->where('product_name', 'like', "%{$search_keyword}%")
                    ->orWhere('price', 'like', "%{$search_keyword}%")
                    ->orWhere('stock', 'like', "%{$search_keyword}%")
                    ->orWhere('comment', 'like', "%{$search_keyword}%");
            });
        } elseif (empty($search_keyword) && !empty($search_company)) {
            $query->whereHas('company', function ($q) use ($search_company) {
                $q->where('company_name', $search_company);
            });
        } else {
            $query = Products::with('company:id,company_name');
        }
        $products = $query->sortable()->orderByDesc('id')->paginate(5);
        $products->withPath('');
        $param['keyword'] = $request->keyword;
        $param['company'] = $request->company;
        return response()->json(
            compact('products', 'param'),
            200,
            [],
            JSON_UNESCAPED_UNICODE
        );
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
