<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomersController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()){
            $customers = Customer::query();

            $sortCol = null;
            $sortDir = null;

           if($request->has('order') && $request->get('order')) {
               $sortCol = $request->get('order')[0]['name'];
               $sortDir = $request->get('order')[0]['dir'];

              if($sortCol == 'DT_RowIndex') {
                  $sortCol = null;
                  $sortDir = null;
              }
           }

           if($sortCol) {
               $customers = $customers->orderBy($sortCol, $sortDir ?? 'asc');
           }

           $customers = $customers->get();

            return DataTables::of($customers)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('customers._actions', ['customer' => $row])->render();
                })
                ->rawColumns(['action'])

                ->make(true);
        }

        return view('customers.index');
    }

    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2'
        ]);

        if($request->id) {
           Customer::find($request->id)->update($request->all());
        } else {
           Customer::create($request->all());
        }

        return response()->json(['message' => 'Customer Created Successfully!']);

    }

    public function edit(Request $request, Customer $customer)
    {
        return response()->json($customer);
    }

    public function show(Request $request, Customer $customer)
    {
        return view('customers.show', ['customer' => $customer]);
    }

    public function destroy(Request $request, Customer $customer)
    {
        $customer->delete();

        return response()->json(['message' => 'Customer Deleted Successfully!']);
    }
}
