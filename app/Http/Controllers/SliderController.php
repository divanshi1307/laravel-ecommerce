<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use App\Models\Upload;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Slider::query();
        if ($request->title) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }
        $sliders = $query->orderBy('id', 'desc')->paginate(10);
        return view('sliders.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sliders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner_link' => 'nullable|url',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $imageId = null;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . rand(100, 999) . "." . $extension;
            $fileSize = $file->getSize();

            $file->move(public_path('uploads/sliders'), $fileName);
            $upload = Upload::create([
                'file_original_name' => $originalName,
                'file_name' => $fileName,
                'user_id' => auth()->id(),
                'file_size' => $fileSize,
                'extension' => $extension,
                'type' => 'slider',
                'alt_tag' => $request->title,
            ]);

            $imageId = $upload->id;
        }

        Slider::create([
            'title' => $validated['title'],
            'photo' => $imageId,
            'banner_link' => $validated['banner_link'] ?? null,
            'start_date' => !empty($validated['start_date']) ? Carbon::parse($validated['start_date']) : null,
            'end_date' => !empty($validated['end_date']) ? Carbon::parse($validated['end_date']) : null,
        ]);
        return redirect()->route('sliders.index')->with('success', 'Slider added successfully.');
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
        $slider = Slider::findOrFail($id);
        return view('sliders.edit', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner_link' => 'nullable|url',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $imageId = $slider->photo; 

        if ($request->hasFile('photo')) {
            if ($slider->photo) {
                $oldUpload = \App\Models\Upload::find($slider->photo);
                if ($oldUpload && file_exists(public_path('uploads/sliders/' . $oldUpload->file_name))) {
                    unlink(public_path('uploads/sliders/' . $oldUpload->file_name));
                }
                if ($oldUpload) {
                    $oldUpload->delete();
                }
            }

            $file = $request->file('photo');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . rand(100, 999) . "." . $extension;
            $fileSize = $file->getSize();

            $file->move(public_path('uploads/sliders'), $fileName);

            $upload = Upload::create([
                'file_original_name' => $originalName,
                'file_name' => $fileName,
                'user_id' => auth()->id(),
                'file_size' => $fileSize,
                'extension' => $extension,
                'type' => 'slider',
                'alt_tag' => $request->title,
            ]);

            $imageId = $upload->id;
        }

        $slider->update([
            'title' => $validated['title'],
            'photo' => $imageId,
            'banner_link' => $validated['banner_link'] ?? null,
            'start_date' => !empty($validated['start_date']) ? Carbon::parse($validated['start_date']) : null,
            'end_date' => !empty($validated['end_date']) ? Carbon::parse($validated['end_date']) : null,
        ]); 
        return redirect()->route('sliders.index')->with('success', 'Slider updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);
        $slider->delete();
        return redirect()->route('sliders.index')->with('success', 'Slider deleted successfully.');
    }
}
