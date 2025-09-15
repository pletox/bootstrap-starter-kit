<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = Category::query();

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
                $categories = $categories->orderBy($sortCol, $sortDir ?? 'asc');
            }

            $filterCount = $categories->clone()->count();
            $totalCount = Category::count();

            $categories = $categories->skip($request->start ?? 0)
                ->take($request->length ?? 10);

            $categories = $categories->get();

            return DataTables::of($categories)
                ->with([
                    "recordsTotal" => $totalCount,
                    "recordsFiltered" => $filterCount,
                ])
                ->skipPaging()
                ->addIndexColumn()
                ->addColumn('select', function ($row) {
                    return view('categories.columns._select', ['category' => $row])->render();
                })
                ->editColumn('description', function ($row) {
                    return view('categories.columns._description', ['category' => $row])->render();
                })
                ->addColumn('action', function ($row) {
                    return view('categories.columns._actions', ['category' => $row])->render();
                })
                ->rawColumns(['action', 'select', 'description'])
                ->make(true);
        }

        return view('categories.index');
    }

    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2'
        ]);

        if ($request->id) {
            Category::find($request->id)->update($request->all());
        } else {
            Category::create($request->all());
        }

        return response()->json(['message' => 'Category Created Successfully!']);

    }

    public function edit(Request $request, Category $category)
    {
        return response()->json($category);
    }

    public function destroy(Request $request, Category $category)
    {
        $category->delete();

        return response()->json(['message' => 'Category Deleted Successfully!']);
    }
}
