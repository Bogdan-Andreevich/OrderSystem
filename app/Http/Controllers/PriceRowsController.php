<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PriceRows;

class PriceRowsController extends Controller
{
    public function create()
    {
        $priceRows = PriceRows::all();
        return response()->json($priceRows->map(function ($row) {
            $row->categories = json_decode($row->categories, true);
            return $row;
        }));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'categoryId' => 'required|integer',
            'title' => 'required|string',
            'categories' => 'required|array',
        ]);
        $validated['categories'] = json_encode($validated['categories']);
        $priceRows = PriceRows::create($validated);

        return response()->json($priceRows);

    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'categoryId' => 'required|integer',
            'title' => 'required|string',
            'categories' => 'required|array',
        ]);
        $validated['categories'] = json_encode($validated['categories']);
        $priceRows = PriceRows::findOrFail($id);
        $priceRows->update($validated);
    }

//    public function destroy($id)
//    {
//        $PriceRowss = PriceRows::findOrFail($id);
//        $PriceRowss->delete();
//        return response()->json($PriceRowss);
//    }
}
