<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Book;

class Author extends Model
{
    use HasFactory;
    // use SoftDeletes;
    protected $fillable = [
        'name',
        'gender',
        'country',
        'imageauthor',
        'description',
        'date_nais',
        'email',
    ];

    // protected $dates = ['deleted_at'];

    // MyApp Personal Access Client

    public function books()
    {
        return $this->hasMany(Book::class);
    }


    public function events()
    {
        return $this->hasMany(Event::class); // Un auteur a plusieurs événements
    }
}
