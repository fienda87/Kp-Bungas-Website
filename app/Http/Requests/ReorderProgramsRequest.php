<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReorderProgramsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'programs' => ['required', 'array', 'min:1'],
            'programs.*.id' => ['required', 'integer', 'exists:programs,id'],
            'programs.*.order' => ['required', 'integer', 'min:1'],
        ];
    }
}
