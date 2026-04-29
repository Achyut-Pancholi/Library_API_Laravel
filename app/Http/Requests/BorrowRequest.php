<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BorrowRequest extends FormRequest
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
        return [
            'book_id' => [
                'required',
                'exists:books,id',
                function ($attribute, $value, $fail) {
                    $book = \App\Models\Book::find($value);
                    if ($book) {
                        $activeBorrows = $book->borrowRecords()->whereNull('returned_at')->count();
                        if ($activeBorrows >= $book->total_copies) {
                            $fail('No copies available for borrowing.');
                        }
                    }
                },
            ],
        ];
    }
}
