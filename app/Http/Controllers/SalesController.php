<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()){
            $sales = Sale::with('customer')->get();

            return DataTables::of($sales)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('sales._actions', ['sale' => $row])->render();
                })
                ->rawColumns(['action'])

                ->make(true);
        }

        return view('sales.index');
    }

    public function create(Request $request)
    {
        $customers = Customer::all();
        $products = Product::all();

        return view('sales.create', compact('customers', 'products'));
    }

}
