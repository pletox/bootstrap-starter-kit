<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home.index');
    }

    public function counters(): JsonResponse
    {
        return response()->json([
            'counters' => [
                'total' => Category::count(),
                'active' => Category::where('active', 1)->count(),
                'inactive' => Category::where('active', 0)->count(),
            ],
        ]);
    }

    public function recentCategories(Request $request): JsonResponse
    {
        $categories = Category::latest()
            ->paginate(
                perPage: 6,
                columns: ['id', 'name', 'active', 'created_at'],
                page: $request->integer('page', 1),
            );

        return response()->json([
            'items' => $categories->getCollection()->map(fn (Category $category): array => [
                'id' => $category->id,
                'name' => $category->name,
                'status' => $category->active ? 'Active' : 'Inactive',
                'status_color' => $category->active ? 'success' : 'warning',
                'created_at' => $category->created_at?->diffForHumans(),
            ]),
            'pagination' => [
                'current_page' => $categories->currentPage(),
                'next_page' => $categories->hasMorePages() ? $categories->currentPage() + 1 : null,
                'has_more' => $categories->hasMorePages(),
            ],
        ]);
    }
}
