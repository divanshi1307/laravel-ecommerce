<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgeGroup;

class AgeGroupController extends Controller
{
    public function index(Request $request)
    {
        $query = AgeGroup::query();
        if ($request->name) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }

        $ageGroups = $query->paginate(10)->appends($request->all());
        return view('admin.age_groups.index', compact('ageGroups'));
    }

    public function create()
    {
        return view('admin.age_groups.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:age_groups,name']);
        AgeGroup::create([
            'name' => $request->name,
            'slug' => \Str::slug($request->name, '-')
        ]);
        return redirect()->route('age-groups.index')->with('success', 'Age Group added successfully.');
    }

    public function edit(AgeGroup $age_group)
    {
        return view('admin.age_groups.edit', compact('age_group'));
    }

    public function update(Request $request, AgeGroup $age_group)
    {
        $request->validate([
            'name' => 'required|string|unique:age_groups,name,' . $age_group->id
        ]);

        $age_group->update([
            'name' => $request->name,
            'slug' => \Str::slug($request->name, '-'),
        ]);
        return redirect()->route('age-groups.index')->with('success', 'Age Group Updated Successfully.');
    }

    public function destroy(AgeGroup $age_group)
    {
        $age_group->delete();
        return redirect()->route('age-groups.index')->with('success', 'Age Group Deleted Successfully.');
    }
}
