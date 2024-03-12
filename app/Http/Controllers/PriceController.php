<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Price;
use Illuminate\Support\Facades\Validator;
use function Symfony\Component\Translation\t;

class PriceController extends Controller
{
    public function create()
    {
        try {
            $prices = Price::all();
            if ($prices->count() === 0) {
                return response()->json(['message' => 'Масив питань порожній']);
            }
            return response()->json($prices);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ціни не знайдено'], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'categoryId' => 'required|integer',
            'name' => 'required|string',
            'nameRu' => 'required|string',
            'unit' => 'required|string',
            'price' => 'required|string',
            'techDocumentations' => 'array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $validatedData = $validator->validated();
            $validatedData['techDocumentations'] = json_encode($validatedData['techDocumentations']);
            $prices = Price::create($validatedData);
            return response()->json($prices);
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'categoryId' => 'required|integer',
            'name' => 'required|string',
            'nameRu' => 'required|string',
            'unit' => 'required|string',
            'price' => 'required|string',
            'techDocumentations' => 'array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $validatedData = $validator->validated();
            $validatedData['techDocumentations'] = json_encode($validatedData['techDocumentations']);
            $prices = Price::findOrFail($id);
            $prices->update($validatedData);
            return response()->json($prices);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ціну на оновлення не знайдено'], 500);
        }
    }

    public function findByCategoryId(int $categoryId)
    {
        $prices = Price::where('categoryId', $categoryId)->get();

        if ($prices->isEmpty()) {
            return null; // Or any other handling you prefer for 'not found' cases
        }

        return $prices;
    }



    public function destroy($id)
    {
        try {
            $prices = Price::findOrFail($id);
            $prices->delete();
            return response()->json(['message' => 'Ціна видалена']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ціну не знайдено'], 500);
        }
    }
}
