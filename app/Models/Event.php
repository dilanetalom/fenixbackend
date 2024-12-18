<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Event extends Model
{
    use HasFactory;

    protected $fillable = [
       'name',
       'description',
       'eventdate',
       'enddate',
       'type',
       'frome',
       'image',
       'user_id',
       'author_id',
    ];

    // protected $dates = ['deleted_at'];
    // MyApp Personal Access Client

    public function author()
    {
        return $this->belongsTo(Author::class); // Un événement appartient à un auteur
    }
}
