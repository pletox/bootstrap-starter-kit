<?php

namespace App\Http\Controllers;

use App\Models\QuickLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuickLinksController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $links = QuickLink::latest()
            ->paginate(
                perPage: 5,
                columns: ['id', 'title', 'url', 'created_at'],
                page: $request->integer('page', 1),
            );

        return response()->json([
            'items' => $links->getCollection()->map(fn (QuickLink $link): array => $this->quickLinkPayload($link)),
            'pagination' => [
                'current_page' => $links->currentPage(),
                'next_page' => $links->hasMorePages() ? $links->currentPage() + 1 : null,
                'has_more' => $links->hasMorePages(),
            ],
        ]);
    }

    public function storeOrUpdate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id' => ['nullable', 'integer', 'exists:quick_links,id'],
            'title' => ['required', 'string', 'min:2', 'max:120'],
            'url' => ['required', 'url', 'max:2048'],
        ]);

        $link = QuickLink::updateOrCreate(
            ['id' => $validated['id'] ?? null],
            [
                'title' => $validated['title'],
                'url' => $validated['url'],
            ],
        );

        return response()->json([
            'message' => $link->wasRecentlyCreated ? 'Quick link created successfully.' : 'Quick link updated successfully.',
            'item' => $this->quickLinkPayload($link),
        ]);
    }

    public function edit(QuickLink $quickLink): JsonResponse
    {
        return response()->json($quickLink);
    }

    public function destroy(QuickLink $quickLink): JsonResponse
    {
        $quickLink->delete();

        return response()->json(['message' => 'Quick link deleted successfully.']);
    }

    private function quickLinkPayload(QuickLink $link): array
    {
        return [
            'id' => $link->id,
            'title' => $link->title,
            'url' => $link->url,
            'created_at' => $link->created_at?->diffForHumans(),
        ];
    }
}
