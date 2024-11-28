<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $table = "restaurant";
    protected $primaryKey = "id_restaurant";
    public $timestamps = false;
    
    public function categories()
{
    return $this->belongsToMany(CategorieRestaurant::class, 'a_pour_categorie', 'id_restaurant', 'id_categorie_restaurant');
}
}
