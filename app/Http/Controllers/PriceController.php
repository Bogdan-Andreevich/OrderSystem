<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Price;

class PriceController extends Controller
{
    public function create()
    {
        $prices = Price::all();
        return response()->json($prices);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'categoryId' => 'required|integer',
            'name' => 'required|string',
            'nameRu' => 'required|string',
            'unit' => 'required|string',
            'price' => 'required|string',
            'techDocumentations' => 'required|array',
//            'techDocumentations.*' => 'integer|distinct',
        ]);
        $validated['techDocumentations'] = json_encode($validated['techDocumentations']);
        $prices = Price::create($validated);

        return response()->json($prices);

    }

//    public function update(Request $request, $id)
//    {
//        $validated = $request->validate([
//            'categoryId' => 'required|integer',
//            'name' => 'required|string',
//            'nameRu' => 'required|string',
//            'unit' => 'required|string',
//            'price' => 'required|string',
//            'techDocumentations' => 'required|array',
////            'techDocumentations.*' => 'integer|distinct',
//        ]);
//        $validated['techDocumentations'] = json_encode($validated['techDocumentations']);
//        $prices = Price::findOrFail($id);
//        $prices->update($validated);
//    }

    public function destroy($id)
    {
        $prices = Price::findOrFail($id);
        $prices->delete();
        return response()->json($prices);
    }
}
