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
        $keyword = $request->input('keyword');
        $company = $request->input('company');
        $lower_price = $request->input('lower_price');
        $upper_price = $request->input('upper_price');
        $lower_stock = $request->input('lower_stock');
        $upper_stock = $request->input('upper_stock');
        $sort = $request->input('sort');
        $direction = $request->input('direction');
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
        if ($direction == 'desc') {
            if ($sort == 'id') {
                $products = $query->orderByDesc('id')->paginate(5);
            } elseif ($sort == 'img_path') {
                $products = $query->orderByDesc('img_path')->paginate(5);
            } elseif ($sort == 'product_name') {
                $products = $query->orderByDesc('product_name')->paginate(5);
            } elseif ($sort == 'price') {
                $products = $query->orderByDesc('price')->paginate(5);
            } elseif ($sort == 'stock') {
                $products = $query->orderByDesc('stock')->paginate(5);
            } elseif ($sort == 'company_name') {
                $products = $query->orderByDesc('company_id')->paginate(5);
            } else {
                $products = $query->orderByDesc('id')->paginate(5);
            }
        } elseif ($direction == 'asc') {
            if ($sort == 'id') {
                $products = $query->orderBy('id')->paginate(5);
            } elseif ($sort == 'img_path') {
                $products = $query->orderBy('img_path')->paginate(5);
            } elseif ($sort == 'product_name') {
                $products = $query->orderBy('product_name')->paginate(5);
            } elseif ($sort == 'price') {
                $products = $query->orderBy('price')->paginate(5);
            } elseif ($sort == 'stock') {
                $products = $query->orderBy('stock')->paginate(5);
            } elseif ($sort == 'company_name') {
                $products = $query->orderBy('company_id')->paginate(5);
            } else {
                $products = $query->orderBy('id')->paginate(5);
            }
        } else {
            $products = $query->orderByDesc('id')->paginate(5);
        }
        return response()->json(
            compact('products'),
            200,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }

    public function sortList(Request $request)
    {
        $query = Products::with('company:id,company_name');
        $sort = $request->input('sort');
        $direction = $request->input('direction');
        if ($direction == 'desc') {
            if ($sort == 'id') {
                $products = $query->orderByDesc('id')->paginate(5);
            } elseif ($sort == 'img_path') {
                $products = $query->orderByDesc('img_path')->paginate(5);
            } elseif ($sort == 'product_name') {
                $products = $query->orderByDesc('product_name')->paginate(5);
            } elseif ($sort == 'price') {
                $products = $query->orderByDesc('price')->paginate(5);
            } elseif ($sort == 'stock') {
                $products = $query->orderByDesc('stock')->paginate(5);
            } elseif ($sort == 'company_name') {
                $products = $query->orderByDesc('company_id')->paginate(5);
            } else {
                $products = $query->orderByDesc('id')->paginate(5);
            }
        }
        if ($direction == 'asc') {
            if ($sort == 'id') {
                $products = $query->orderBy('id')->paginate(5);
            } elseif ($sort == 'img_path') {
                $products = $query->orderBy('img_path')->paginate(5);
            } elseif ($sort == 'product_name') {
                $products = $query->orderBy('product_name')->paginate(5);
            } elseif ($sort == 'price') {
                $products = $query->orderBy('price')->paginate(5);
            } elseif ($sort == 'stock') {
                $products = $query->orderBy('stock')->paginate(5);
            } elseif ($sort == 'company_name') {
                $products = $query->orderBy('company_id')->paginate(5);
            } else {
                $products = $query->orderBy('id')->paginate(5);
            }
        }
        return response()->json(
            compact('products'),
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
