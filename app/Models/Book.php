<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;
    protected $fillable = ['author_id', 'title', 'isbn', 'genre', 'published_year', 'total_copies'];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function borrowRecords()
    {
        return $this->hasMany(BorrowRecord::class);
    }

    public function scopeAvailable(Builder $query)
    {
        return $query->where('total_copies', '>', function ($sub) {
            $sub->selectRaw('count(*)')
                ->from('borrow_records')
                ->whereColumn('book_id', 'books.id')
                ->whereNull('returned_at');
        });
    }
}
