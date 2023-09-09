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
        $query = Products::with('company');
        $products = $query->sortable()->orderByDesc('id')->paginate(5);
        return view('list', ['products' => $products, 'companies' => $companies]);
    }

    public function searchList(Request $request)
    {
        $query = Products::with('company:id,company_name');
        $keyword = $request->input('keyword');
        $company = $request->input('company');
        $lower_price = $request->input('lower_price');
        $upper_price = $request->input('upper_price');
        $lower_stock = $request->input('lower_stock');
        $upper_stock = $request->input('upper_stock');
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('product_name', 'like', "%{$keyword}%")
                    ->orWhere('price', 'like', "%{$keyword}%")
                    ->orWhere('stock', 'like', "%{$keyword}%")
                    ->orWhere('comment', 'like', "%{$keyword}%");
            });
        }
        if (!empty($company)) {
            $query->whereHas('company', function ($q) use ($company) {
                $q->where('company_name', $company);
            });
        }
        if (!empty($lower_price)) {
            $query->where('price', '>=', $lower_price);
        }
        if (!empty($upper_price)) {
            $query->where('price', '<=', $upper_price);
        }
        if (!empty($lower_stock)) {
            $query->where('stock', '>=', $lower_stock);
        }
        if (!empty($upper_stock)) {
            $query->where('stock', '<=', $upper_stock);
        }
        $products = $query->sortable()->orderByDesc('id')->paginate(5);
        $products->withPath('');
        $param['keyword'] = $request->keyword;
        $param['company'] = $request->company;
        $param['lower_price'] = $request->input('lower_price');
        $param['upper_price'] = $request->input('upper_price');
        $param['lower_stock'] = $request->input('lower_stock');
        $param['upper_stock'] = $request->input('upper_stock');
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

    public function deleteProduct(Request $request)
    {
        $delete_product = Products::findOrFail($request->id);
        $delete_product->delete();
    }
}
