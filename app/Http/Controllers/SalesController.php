<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\Products;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function buyProducts(Request $request)
    {
        DB::beginTransaction();
        try {
            $sales = new Sales();
            $sales->addSales($request);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
    }
}
