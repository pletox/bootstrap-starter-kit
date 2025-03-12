<?php

namespace App\Http\Controllers\Utilities;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RichTextEditorController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate(['file' => 'image|max:2048']);
        $path = $request->file('file')->store('uploads', 'public');
        return response()->json(['location' => Storage::url($path)]);
    }

    public function mention(Request $request)
    {
        $query = $request->get('query');
        $users = \App\Models\User::where('name', 'LIKE', "%{$query}%")->pluck('name');

        return response()->json($users);
    }
}
