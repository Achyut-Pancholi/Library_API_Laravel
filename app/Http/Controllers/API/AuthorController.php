<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $query = Author::withCount('books')->orderBy('created_at', 'desc');

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nationality', 'like', '%' . $request->search . '%');
        }

        $authors = $query->paginate(10);

        return AuthorResource::collection($authors)->additional([
            'success' => true,
            'message' => 'Authors retrieved successfully',
        ]);
    }

    public function store(AuthorRequest $request)
    {
        $author = Author::create($request->validated());

        return (new AuthorResource($author))->additional([
            'success' => true,
            'message' => 'Author created successfully',
        ])->response()->setStatusCode(201);
    }

    public function show($id)
    {
        $author = Author::with('books')->find($id);

        if (!$author) {
            return response()->json(['success' => false, 'message' => 'Author not found'], 404);
        }

        return (new AuthorResource($author))->additional([
            'success' => true,
            'message' => 'Author retrieved successfully',
        ]);
    }

    public function update(AuthorRequest $request, $id)
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json(['success' => false, 'message' => 'Author not found'], 404);
        }

        $author->update($request->validated());

        return (new AuthorResource($author))->additional([
            'success' => true,
            'message' => 'Author updated successfully',
        ]);
    }

    public function destroy($id)
    {
        $author = Author::withCount('books')->find($id);

        if (!$author) {
            return response()->json(['success' => false, 'message' => 'Author not found'], 404);
        }

        if ($author->books_count > 0) {
            return response()->json(['success' => false, 'message' => 'Cannot delete author with existing books'], 422);
        }

        $author->delete();

        return response()->json([
            'success' => true,
            'message' => 'Author deleted successfully',
            'data' => []
        ]);
    }
}
