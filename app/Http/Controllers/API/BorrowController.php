<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\BorrowRequest;
use App\Http\Resources\BorrowResource;
use App\Models\Book;
use App\Models\BorrowRecord;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    public function store(BorrowRequest $request)
    {
        $book = Book::find($request->book_id);

        $borrowRecord = BorrowRecord::create([
            'user_id' => $request->user()->id,
            'book_id' => $book->id,
            'borrowed_at' => now(),
            'due_date' => now()->addDays(7),
        ]);

        $borrowRecord->load('book');

        return (new BorrowResource($borrowRecord))->additional([
            'success' => true,
            'message' => 'Book borrowed successfully',
        ])->response()->setStatusCode(201);
    }

    public function history(Request $request)
    {
        $records = BorrowRecord::with('book')
            ->where('user_id', $request->user()->id)
            ->orderBy('borrowed_at', 'desc')
            ->paginate(10);

        return BorrowResource::collection($records)->additional([
            'success' => true,
            'message' => 'Borrow history retrieved successfully',
        ]);
    }

    public function returnBook(Request $request, $id)
    {
        $record = BorrowRecord::find($id);

        if (!$record) {
            return response()->json(['success' => false, 'message' => 'Borrow record not found'], 404);
        }

        if ($record->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Forbidden. You can only return your own borrowed books.'], 403);
        }

        if ($record->returned_at) {
            return response()->json(['success' => false, 'message' => 'Book is already returned.'], 422);
        }

        $record->update([
            'returned_at' => now()
        ]);

        $record->load('book');

        return (new BorrowResource($record))->additional([
            'success' => true,
            'message' => 'Book returned successfully',
        ]);
    }
}
