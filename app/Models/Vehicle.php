<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $table = 'vehicle';
    protected $fillable = [
        'user_id',
        'brand_id',
        'brandname',
        'color',
        'description',
        'model',
        'varient',
        'chasie_number',
        'engine_number',
        'purchase_price',
        'expense',
        'sale_price',
        'image_path',
        'buyer_id',
        'seller_id',
        'document',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public static function GetAllvehicle(){
        
        return self::all();
    }
}
