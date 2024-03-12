<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PriceRows;
use App\Models\Price;
use Illuminate\Support\Facades\Validator;

class PriceRowsController extends Controller
{
    public function create()
    {
        try {
            $priceRows = PriceRows::all();
            if ($priceRows->count() === 0) {
                return response()->json(['message' => 'Масив питань порожній']);
            }
            return response()->json($priceRows->map(function ($row) {
                $row->categories = json_decode($row->categories, true);
                return $row;
            }));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Помилка отримання даних'], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'priceId' => 'required|integer',
            'categories' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $validatedData = $validator->validated();
            $validatedData['categories'] = json_encode($validatedData['categories']);
            $priceRows = PriceRows::create($validatedData);

            $formattedData = [
                'id' => $priceRows->id,
                'priceId' => $priceRows->priceId,
                'categories' => json_decode($priceRows->categories, true),
                'updated_at' => $priceRows->updated_at,
                'created_at' => $priceRows->created_at,
            ];
            return response()->json($formattedData);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Помилка створення рядку прайсу'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'priceId' => 'required|integer',
            'categories' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $validatedData = $validator->validated();
            $validatedData['categories'] = json_encode($validatedData['categories']);
            $priceRows = PriceRows::findOrFail($id);
            $priceRows->update($validatedData);

            $formattedData = [
                'id' => $priceRows->id,
                'priceId' => $priceRows->priceId,
                'categories' => json_decode($priceRows->categories, true),
                'updated_at' => $priceRows->updated_at,
                'created_at' => $priceRows->created_at,
            ];
            return response()->json($formattedData);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Помилка оновлення рядку прайсу'], 500);
        }
    }

    public function getById(int $priceId)
    {
        try {
            $priceRow = PriceRows::where('id', $priceId)->firstOrFail();

            $categories = json_decode($priceRow->categories, true);


            $prices = Price::whereIn('id', $categories)->get(); 

            $formattedData = [
                'id' => $priceRow->id,
                'priceId' => $priceRow->priceId,
                'categoriesOrders' => $categories, // Decode the JSON array
                'categories' => $prices, // Decode the JSON array
                'updated_at' => $priceRow->updated_at,
                'created_at' => $priceRow->created_at,
            ];

            return response()->json($formattedData);
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 500);
        }
    }
}
