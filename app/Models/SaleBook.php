<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleBook extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable =[
        'quantity',
        'total_price',
        'type_book',
        'book_id',
        'sale_id',
    ];

    protected $dates = ['deleted_at'];


}
