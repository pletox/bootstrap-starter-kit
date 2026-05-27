<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Yajra\DataTables\Facades\DataTables;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = Category::query()
                ->when($request->filled('q'), function ($query) use ($request) {
                    $query->where(function ($query) use ($request) {
                        $query->where('name', 'like', '%'.$request->q.'%')
                            ->orWhere('description', 'like', '%'.$request->q.'%');
                    });
                })
                ->when($request->filled('active'), function ($query) use ($request) {
                    $query->where('active', $request->integer('active'));
                })
                ->when($request->filled('created_from'), function ($query) use ($request) {
                    $query->whereDate('created_at', '>=', $request->date('created_from'));
                })
                ->when($request->filled('created_to'), function ($query) use ($request) {
                    $query->whereDate('created_at', '<=', $request->date('created_to'));
                });

            $sortCol = null;
            $sortDir = null;

            if ($request->has('order') && $request->get('order')) {
                $order = $request->input('order.0', []);
                $columnIndex = $order['column'] ?? null;
                $sortCol = $order['name']
                    ?? data_get($request->input('columns', []), "{$columnIndex}.name")
                    ?? data_get($request->input('columns', []), "{$columnIndex}.data");
                $sortDir = $order['dir'] ?? 'asc';

                if ($sortCol == 'DT_RowIndex') {
                    $sortCol = null;
                    $sortDir = null;
                }
            }

            if ($sortCol && in_array($sortCol, ['id', 'name', 'description', 'active', 'created_at', 'updated_at'])) {
                $categories = $categories->orderBy($sortCol, $sortDir ?? 'asc');
            }

            $filterCount = $categories->clone()->count();
            $totalCount = Category::count();

            $categories = $categories->skip($request->start ?? 0)
                ->take($request->length ?? 10);

            $categories = $categories->get();

            $request->query->remove('order');
            $request->request->remove('order');

            return DataTables::of($categories)
                ->with([
                    'recordsTotal' => $totalCount,
                    'recordsFiltered' => $filterCount,
                ])
                ->skipPaging()
                ->addIndexColumn()
                ->addColumn('select', function ($row) {
                    return view('categories.columns._select', ['category' => $row])->render();
                })
                ->editColumn('description', function ($row) {
                    return view('categories.columns._description', ['category' => $row])->render();
                })
                ->addColumn('status', function ($row) {
                    return view('categories.columns._status', ['category' => $row])->render();
                })
                ->addColumn('action', function ($row) {
                    return view('categories.columns._actions', ['category' => $row])->render();
                })
                ->rawColumns(['action', 'select', 'description', 'status'])
                ->make(true);
        }

        return view('categories.index');
    }

    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2',
        ]);

        if ($request->id) {
            $category = Category::find($request->id);
            $category->update($request->all());
        } else {
            $category = Category::create($request->all());
        }

        return response()->json([
            'message' => $request->id ? 'Category Updated Successfully!' : 'Category Created Successfully!',
            'item' => $this->datatableRow($category->fresh()),
        ]);

    }

    public function edit(Request $request, Category $category)
    {
        return response()->json($category);
    }

    public function options(Request $request)
    {
        $categories = Category::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->q.'%');
            })
            ->when($request->filled('id'), function ($query) use ($request) {
                $query->whereIn('id', (array) $request->id);
            })
            ->orderBy('name')
            ->paginate(10);

        return response()->json([
            'results' => $categories->getCollection()->map(fn ($category) => [
                'id' => $category->id,
                'text' => $category->name,
            ]),
            'pagination' => [
                'more' => $categories->hasMorePages(),
            ],
        ]);
    }

    public function destroy(Request $request, Category $category)
    {
        $category->delete();

        return response()->json(['message' => 'Category Deleted Successfully!']);
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:categories,id',
        ]);

        $query = Category::whereIn('id', $validated['ids']);
        $count = $query->count();

        match ($validated['action']) {
            'delete' => $query->delete(),
            'activate' => $query->update(['active' => 1]),
            'deactivate' => $query->update(['active' => 0]),
        };

        $message = match ($validated['action']) {
            'delete' => "{$count} categories deleted successfully.",
            'activate' => "{$count} categories marked active.",
            'deactivate' => "{$count} categories marked inactive.",
        };

        return response()->json(['message' => $message]);
    }

    public function export(Request $request): StreamedResponse
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:categories,id',
        ]);

        $categories = Category::whereIn('id', $validated['ids'])
            ->orderBy('name')
            ->get(['id', 'name', 'description', 'active', 'created_at']);

        return response()->streamDownload(function () use ($categories) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['ID', 'Name', 'Description', 'Status', 'Created At']);

            foreach ($categories as $category) {
                fputcsv($handle, [
                    $category->id,
                    $category->name,
                    $category->description,
                    $category->active ? 'Active' : 'Inactive',
                    $category->created_at?->toDateTimeString(),
                ]);
            }

            fclose($handle);
        }, 'categories.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function datatableRow(Category $category): array
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'description' => view('categories.columns._description', ['category' => $category])->render(),
            'status' => view('categories.columns._status', ['category' => $category])->render(),
            'action' => view('categories.columns._actions', ['category' => $category])->render(),
        ];
    }
}
