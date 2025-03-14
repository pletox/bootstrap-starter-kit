<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::with('category')->latest()->get();


            return Datatables::of($products)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('products._actions', ['product' => $row])->render();
                })
                ->addColumn('status', function ($row) {
                    return view('products._status', ['product' => $row])->render();
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        $categories = Category::all();

        return view('products.index', compact('categories'));
    }

    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'date' => 'required',
        ]);

        if ($request->id) {
            Product::find($request->id)->update($request->all());
        } else {
            Product::create($request->all());
        }

        return response()->json(['message' => 'Product saved successfully.']);
    }

    public function edit(Product $product)
    {
        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully.']);
    }
}
