<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TenantsController extends Controller
{
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:120'],
        ]);

        $tenant = $request->user()->tenants()->create([
            'name' => $validated['name'],
            'owner_id' => $request->user()->getKey(),
        ], [
            'role' => 'owner',
        ]);

        $request->user()->switchTenant($tenant);

        return $this->tenantResponse($request, 'Workspace created successfully.');
    }

    public function switch(Request $request, Tenant $tenant): JsonResponse|RedirectResponse
    {
        $request->user()->switchTenant($tenant);

        return $this->tenantResponse($request, 'Workspace switched successfully.');
    }

    private function tenantResponse(Request $request, string $message): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $message]);
        }

        return back()->with('status', $message);
    }
}