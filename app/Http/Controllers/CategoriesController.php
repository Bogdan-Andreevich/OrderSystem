<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;
use Illuminate\Support\Facades\Validator;


class CategoriesController extends Controller
{
    public function create()
    {
        try {
            $categories = Categories::all();
            return response()->json($categories);
        } catch (\Exception $e) {
            return response()->json([
                'error' => "Помилка при отриманні категорій: " . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'text' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $category = Categories::create($validator->validated());
            return response()->json($category);
        } catch (\Exception $e) {
            return response()->json([
                'error' => "Помилка створення категорії: " . $e->getMessage()
            ], 500);
        }

    }

//    public function update(Request $request, $id)
//    {
////        $validated = $request->validate([
////            'name' => 'required|string',
////            'text' => 'required|string',
////        ]);
////        $categories = Categories::findOrFail($id);
////        $categories->update($validated);
//
//
//        {
//            $validator = Validator::make($request->all(), [
//                'name' => 'required|string',
//                'text' => 'required|string',
//            ]);
//            if ($validator->fails()) {
//                return response()->json([
//                    'errors' => $validator->errors()
//                ], 422);
//            }
//
//            try {
//                $category = Categories::findOrFail($id);
//                $validated = $validator->validated();
//                $category->update($validated);
//                return response()->json($category);
//            } catch (\Exception $e) {
//                return response()->json([
//                    'error' => "Помилка оновлення категорії: " . $e->getMessage()
//                ], 500);
//            }
//        }
//    }

    public function destroy($id)
    {
        try {
            $category = Categories::findOrFail($id);
            $category->delete();
            return response()->json([
                'message' => "Категорію успішно видалено."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => "Помилка видалення категорії: " . $e->getMessage()
            ], 500);
        }
    }
}
