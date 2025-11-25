<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Location::query();
        if ($request->filled('state')) {
            $query->where('state', $request->state);
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        if ($request->filled('pincode')) {
            $query->where('pincode', $request->pincode);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $locations = $query->orderBy('id', 'desc')->paginate(10);
        return view('locations.index', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('locations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'pincode' => 'required|string|max:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Location::create($request->only(['state', 'city', 'pincode']));
        return redirect()->route('locations.index')->with('success', 'Location added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $location = Location::findOrFail($id);
        return view('locations.edit', compact('location'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $location = Location::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'pincode' => 'required|string|max:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $location->update($request->only(['state', 'city', 'pincode']));
        return redirect()->route('locations.index')->with('success', 'Location updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $location= Location::destroy($id); 
        return redirect()->route('locations.index')->with('success', 'Location deleted successfully.');
    }

    public function updateStatus(Request $request)
    {
        $location = Location::find($request->id);
        if (!$location) {
            return response()->json(['success' => false, 'message' => 'Location not found']);
        }
        $location->is_active = $request->is_active;
        $location->save();

        $statusText = $location->is_active ? 'Active' : 'Inactive';
        return response()->json(['success' => true, 'message' => "Status updated to $statusText"]);
    }
}
