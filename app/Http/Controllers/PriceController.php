<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Price;
use App\Models\PriceRows;
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
            'nameRu' => 'nullable|string',
            'unit' => 'string',
            'price' => 'nullable|string',
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
            'nameRu' => 'nullable|string',
            'unit' => 'string',
            'price' => 'nullable|string',
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
        $pricesRows = PriceRows::all();

        foreach ($pricesRows as &$question) {
            $question->categories = json_encode($question->categories);
        }

        foreach ($prices as &$question) {
            $question->techDocumentations = PriceRows::where('categories->id', $question->id)->get();
        }

        return [$prices, $pricesRows];
    }

    public function findByCategoryAll()
    {
        $prices = Price::all();
        $pricesRows = PriceRows::all();

        foreach ($pricesRows as &$question) {
            $question->categories = json_encode($question->categories);
        }

        foreach ($prices as &$question) {
            $question->techDocumentations = PriceRows::where('categories->id', $question->id)->get();
        }

        return [$prices, $pricesRows];
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
