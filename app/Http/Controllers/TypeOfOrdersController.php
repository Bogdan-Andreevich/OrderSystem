<?php

namespace App\Http\Controllers;

use App\Models\TypeOfOrders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // For validation

class TypeOfOrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = TypeOfOrders::all();
        return response()->json($orders); // Assuming you want a JSON response
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'categoryId' => 'required|integer',
            'searchId' => 'required|integer',
            'name' => 'required|string|max:255',
            'nameRu' => 'nullable|string|max:255',
            'techDocumentations' => 'nullable|json',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422); // 422 for validation errors
        }

        $newOrder = TypeOfOrders::create($validator->validated());

        return response()->json($newOrder, 201); // 201 for 'created'
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'categoryId' => 'required|integer',
            'searchId' => 'required|integer',
            'name' => 'required|string|max:255',
            'nameRu' => 'nullable|string|max:255',
            'techDocumentations' => 'nullable|json'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $validatedData = $validator->validated();
        $validatedData['techDocumentations'] = json_encode($validatedData['techDocumentations']);
        $prices = TypeOfOrders::findOrFail($id);
        $prices->update($validatedData);
        return response()->json($prices);
    }
    
    public function destroy($id)
    {
        try {
            $prices = TypeOfOrders::findOrFail($id);
            $prices->delete();
            return response()->json(null, 204); // 204 for 'No Content' on successful deletion
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error'], 500);
        }
    }


}
