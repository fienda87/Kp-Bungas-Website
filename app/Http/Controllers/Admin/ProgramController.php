<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Services\ProgramService;
use App\Http\Requests\StoreProgramRequest;
use App\Http\Requests\UpdateProgramRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProgramController extends Controller
{
    protected $programService;

    public function __construct(ProgramService $programService)
    {
        $this->programService = $programService;
    }

    public function index()
    {
        return Inertia::render('Admin/Programs/Index', [
            'programs' => Program::orderBy('order')->get()
        ]);
    }

    public function store(StoreProgramRequest $request)
    {
        $this->programService->createProgram(
            $request->validated(),
            $request->file('image')
        );

        return redirect()->route('admin.programs.index')->with('success', 'Program created successfully.');
    }

    public function update(UpdateProgramRequest $request, Program $program)
    {
        $this->programService->updateProgram(
            $program,
            $request->validated(),
            $request->file('image')
        );

        return redirect()->route('admin.programs.index')->with('success', 'Program updated successfully.');
    }

    public function destroy(Program $program)
    {
        $this->programService->deleteProgram($program);

        return redirect()->route('admin.programs.index')->with('success', 'Program deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $this->programService->reorderPrograms($request->input('programs', []));

        return redirect()->back()->with('success', 'Programs reordered successfully.');
    }
}
