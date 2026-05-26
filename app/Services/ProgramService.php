<?php

namespace App\Services;

use App\Models\Program;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProgramService
{
    public function createProgram(array $data, ?UploadedFile $image): Program
    {
        if ($image) {
            $data['image'] = $image->store('programs', 'public');
        }
        return Program::create($data);
    }

    public function updateProgram(Program $program, array $data, ?UploadedFile $image): Program
    {
        if ($image) {
            if ($program->image) {
                Storage::disk('public')->delete($program->image);
            }
            $data['image'] = $image->store('programs', 'public');
        }
        $program->update($data);
        return $program;
    }

    public function deleteProgram(Program $program): void
    {
        if ($program->image) {
            Storage::disk('public')->delete($program->image);
        }
        $program->delete();
    }

    public function reorderPrograms(array $orderData): void
    {
        foreach ($orderData as $item) {
            Program::where('id', $item['id'])->update(['order' => $item['order']]);
        }
    }
}
