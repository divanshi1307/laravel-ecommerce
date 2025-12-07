<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GstModule;
use Str;

class GstController extends Controller
{
    // Display a listing of GST entries
    public function index(Request $request)
    {
        $query = GstModule::query();

        if ($request->gst_percentage) {
            $query->where('gst_percentage', $request->gst_percentage);
        }

        $gstModules = $query->paginate(10)->appends($request->all());

        return view('admin.gst_modules.index', compact('gstModules'));
    }

    // Show the form for creating a new GST entry
    public function create()
    {
        return view('admin.gst_modules.create');
    }

    // Store a newly created GST entry
    public function store(Request $request)
    {
        $request->validate([
            'gst_percentage' => ['required', 'regex:/^\d+(\.\d+)?%$/', 'unique:gst_modules,gst_percentage'],
        ]);

        GstModule::create([
            'gst_percentage' => $request->gst_percentage,
            'slug' => Str::slug($request->gst_percentage)
        ]);

        return redirect()->route('gst-module.index')->with('success', 'GST added successfully.');
    }

    // Show the form for editing a GST entry
    public function edit(GstModule $gst_module)
    {
        return view('admin.gst_modules.edit', compact('gst_module'));
    }

    // Update the specified GST entry
    public function update(Request $request, GstModule $gst_module)
    {
        $request->validate([
            'gst_percentage' => ['required', 'regex:/^\d+(\.\d+)?%$/', 'unique:gst_modules,gst_percentage,' . $gst_module->id],
        ]);

        $gst_module->update([
            'gst_percentage' => $request->gst_percentage,
            'slug' => Str::slug($request->gst_percentage),
        ]);

        return redirect()->route('gst-module.index')
                         ->with('success', 'GST updated successfully.');
    }

    // Delete the specified GST entry
    public function destroy(GstModule $gst_module)
    {
        $gst_module->delete();

        return redirect()->route('gst-module.index')->with('success', 'GST deleted successfully.');
    }
}
