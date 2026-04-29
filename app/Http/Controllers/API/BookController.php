<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('author')
            ->withCount(['borrowRecords as active_borrow_count' => function ($q) {
                $q->whereNull('returned_at');
            }])
            ->orderBy('created_at', 'desc');

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('genre', 'like', '%' . $request->search . '%');
        }

        if ($request->boolean('available')) {
            $query->available();
        }

        $books = $query->paginate(10);

        return BookResource::collection($books)->additional([
            'success' => true,
            'message' => 'Books retrieved successfully',
        ]);
    }

    public function store(BookRequest $request)
    {
        $book = Book::create($request->validated());
        $book->load('author');

        return (new BookResource($book))->additional([
            'success' => true,
            'message' => 'Book created successfully',
        ])->response()->setStatusCode(201);
    }

    public function show($id)
    {
        $book = Book::with('author')->withCount(['borrowRecords as active_borrow_count' => function ($q) {
            $q->whereNull('returned_at');
        }])->find($id);

        if (!$book) {
            return response()->json(['success' => false, 'message' => 'Book not found'], 404);
        }

        return (new BookResource($book))->additional([
            'success' => true,
            'message' => 'Book retrieved successfully',
        ]);
    }

    public function update(BookRequest $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['success' => false, 'message' => 'Book not found'], 404);
        }

        $book->update($request->validated());
        $book->load('author');

        return (new BookResource($book))->additional([
            'success' => true,
            'message' => 'Book updated successfully',
        ]);
    }

    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['success' => false, 'message' => 'Book not found'], 404);
        }

        $activeBorrows = $book->borrowRecords()->whereNull('returned_at')->count();

        if ($activeBorrows > 0) {
            return response()->json(['success' => false, 'message' => 'Cannot delete book with active borrows'], 422);
        }

        $book->delete();

        return response()->json([
            'success' => true,
            'message' => 'Book deleted successfully',
            'data' => []
        ]);
    }
}
