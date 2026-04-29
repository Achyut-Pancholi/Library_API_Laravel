<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $active_borrows = $this->active_borrow_count ?? 0;
        return [
            'id' => $this->id,
            'title' => $this->title,
            'isbn' => $this->isbn,
            'genre' => $this->genre,
            'published_year' => $this->published_year,
            'total_copies' => $this->total_copies,
            'available_copies' => max(0, $this->total_copies - $active_borrows),
            'author' => new AuthorResource($this->whenLoaded('author')),
            'active_borrow_count' => $this->whenCounted('borrowRecords'),
        ];
    }
}
