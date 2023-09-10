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
        $sales = new Sales();
        $result = $sales->addSales($request);
        return $result;
    }
}
