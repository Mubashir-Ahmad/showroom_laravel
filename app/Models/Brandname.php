<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brandname extends Model
{
    use HasFactory;
    protected $table = 'brands';
    protected $fillable = [
        'name',
        'logo',
        'user_id'
    ];
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'brand', 'name');
    }
    public static function GetAllbrand(){
        
        return self::all();
    }
}
