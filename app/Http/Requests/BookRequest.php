<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $bookId = $this->route('id');
        return [
            'author_id' => 'required|exists:authors,id',
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn,' . $bookId,
            'genre' => 'required|string|max:255',
            'published_year' => 'required|integer',
            'total_copies' => 'required|integer|min:1',
        ];
    }
}
