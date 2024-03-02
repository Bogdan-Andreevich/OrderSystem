<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;


class CategoriesController extends Controller
{
    public function v()
    {
        $categories = Categories::all();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'text' => 'required|string',
        ]);
        $categories = Categories::create($validated);

        return response()->json($categories);

    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'text' => 'required|string',
        ]);
        $categories = Categories::findOrFail($id);
        $categories->update($validated);
    }

    public function destroy($id)
    {
        $categories = Categories::findOrFail($id);
        $categories->delete();
        return response()->json($categories);
    }
}
