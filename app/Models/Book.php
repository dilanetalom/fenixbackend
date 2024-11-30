<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SaleBook;
use App\Models\Sale;


class Book extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'category',
        'language',
        'image',
        'status',
        'niveau',
        'pub_date',
        'price_n',
        'price_p',
        'user_id',
        'author_id',
        
    ];
    protected $dates = ['deleted_at'];



    public function sales()
    {
        return $this->belongsToMany(Sale::class)
                    ->using(SaleBook::class) // Spécifie le modèle pivot
                    ->withPivot(['quantity', 'total_price', 'type_book']) // Champs supplémentaires
                    ->withTimestamps(); // Ajoute les timestamps si nécessaire
    }


}
