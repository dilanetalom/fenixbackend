<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class News extends Model
{
    use HasFactory;
    // SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'newsdate',
        'image',
        'type',
        'frome',
        'user_id',
        'author_id',
     ];


    //  protected $dates = ['deleted_at'];
     // MyApp Personal Access Client
}
