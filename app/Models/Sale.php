<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SaleBook;
use App\Models\Book;


class Sale extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'sale_date',
        'paiement_type',
        'total_price',
        'status',
    ];
    protected $dates = ['deleted_at'];


    public function books()
    {
        return $this->belongsToMany(Book::class)
                    ->using(SaleBook::class) 
                    ->withPivot(['quantity', 'total_price', 'type_book']) 
                    ->withTimestamps(); 
    }
}
