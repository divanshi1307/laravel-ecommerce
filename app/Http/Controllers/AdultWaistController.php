<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdultWaist;
use Str;

class AdultWaistController extends Controller
{
    public function index(Request $request)
    {
        $query = AdultWaist::query();

        if ($request->waist_size) {
            $query->where('waist_size', 'LIKE', '%' . $request->waist_size . '%');
        }

        $adultWaists = $query->paginate(10)->appends($request->all());

        return view('admin.adult_waists.index', compact('adultWaists'));
    }

    public function create()
    {
        return view('admin.adult_waists.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'waist_size' => 'required|string|unique:adult_waists,waist_size',
        ]);

        AdultWaist::create([
            'waist_size' => $request->waist_size,
            'slug' => Str::slug($request->waist_size)
        ]);

        return redirect()->route('adult-waist.index')->with('success', 'Waist Size added successfully.');
    }

    public function edit(AdultWaist $adult_waist)
    {
        return view('admin.adult_waists.edit', compact('adult_waist'));
    }

    public function update(Request $request, AdultWaist $adult_waist)
    {
        $request->validate([
            'waist_size' => 'required|string|unique:adult_waists,waist_size,' . $adult_waist->id,
        ]);

        $adult_waist->update([
            'waist_size' => $request->waist_size,
            'slug' => Str::slug($request->waist_size),
        ]);

        return redirect()->route('adult-waist.index')->with('success', 'Waist Size updated successfully.');
    }

    public function destroy(AdultWaist $adult_waist)
    {
        $adult_waist->delete();

        return redirect()->route('adult-waist.index')->with('success', 'Waist Size deleted successfully.');
    }
}
