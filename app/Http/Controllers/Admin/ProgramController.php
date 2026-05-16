<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProgramController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Programs/Index', [
            'programs' => Program::orderBy('order')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'is_active' => 'required|boolean',
            'order' => 'required|integer',
            'icon' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('programs', 'public');
        }

        Program::create($validated);

        return redirect()->route('admin.programs.index')->with('success', 'Program created successfully.');
    }

    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'is_active' => 'required|boolean',
            'order' => 'required|integer',
            'icon' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($program->image) {
                Storage::disk('public')->delete($program->image);
            }
            $validated['image'] = $request->file('image')->store('programs', 'public');
        }

        $program->update($validated);

        return redirect()->route('admin.programs.index')->with('success', 'Program updated successfully.');
    }

    public function destroy(Program $program)
    {
        if ($program->image) {
            Storage::disk('public')->delete($program->image);
        }
        $program->delete();

        return redirect()->route('admin.programs.index')->with('success', 'Program deleted successfully.');
    }
}
