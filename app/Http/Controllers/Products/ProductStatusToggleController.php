<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductStatusToggleController extends Controller
{
    public function __invoke(Request $request, Product $product)
    {
        $product->update(['active' => $request->status]);

        return response()->json(['message' => 'Status updated']);
    }

}
