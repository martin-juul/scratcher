<?php
declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\DirectoryExists;
use Illuminate\Foundation\Http\FormRequest;

class LibraryCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'path' => ['required', 'string', new DirectoryExists],
        ];
    }
}
