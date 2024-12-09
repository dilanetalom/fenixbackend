<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Sale extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'sale_date',
        'paiement_type',
        'total_price',
        'status',
        'quantity',
        'type_book',
        'book_id',
        'user_id',
    ];
    protected $dates = ['deleted_at'];


    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    
}
