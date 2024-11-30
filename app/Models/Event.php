<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Event extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
       'name',
       'description',
       'eventdate',
       'image',
       'user_id',
    ];

    protected $dates = ['deleted_at'];
    // MyApp Personal Access Client
}
