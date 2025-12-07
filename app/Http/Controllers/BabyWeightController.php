<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BabyWeight;
use Str;

class BabyWeightController extends Controller
{
    public function index(Request $request)
    {
        $query = BabyWeight::query();

        if ($request->weight_range) {
            $query->where('weight_range', 'LIKE', '%' . $request->weight_range . '%');
        }

        $babyWeights = $query->paginate(10)->appends($request->all());

        return view('admin.baby_weights.index', compact('babyWeights'));
    }

    public function create()
    {
        return view('admin.baby_weights.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'weight_range' => 'required|string|unique:baby_weights,weight_range',
        ]);

        BabyWeight::create([
            'weight_range' => $request->weight_range,
            'slug' => Str::slug($request->weight_range)
        ]);

        return redirect()->route('baby-weight.index')->with('success', 'Baby Weight added successfully.');
    }

    public function edit(BabyWeight $baby_weight)
    {
        return view('admin.baby_weights.edit', compact('baby_weight'));
    }

    public function update(Request $request, BabyWeight $baby_weight)
    {
        $request->validate([
            'weight_range' => 'required|string|unique:baby_weights,weight_range,' . $baby_weight->id,
        ]);

        $baby_weight->update([
            'weight_range' => $request->weight_range,
            'slug' => Str::slug($request->weight_range)
        ]);

        return redirect()->route('baby-weight.index')->with('success', 'Baby Weight updated successfully.');
    }

    public function destroy(BabyWeight $baby_weight)
    {
        $baby_weight->delete();

        return redirect()->route('baby-weight.index')->with('success', 'Baby Weight deleted successfully.');
    }
}
