<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieRestaurant extends Model
{
    use HasFactory;

    protected $table = 'categorie_restaurant';
    protected $primaryKey = 'id_categorie_restaurant';
    protected $fillable = ['libelle'];

    public function restaurants()
    {
        return $this->belongsToMany(Restaurant::class, 'a_pour_categorie', 'id_categorie_restaurant', 'id_restaurant');
    }
}
