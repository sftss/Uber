<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panier extends Model
{
    use HasFactory;
    protected $table = "panier";
     protected $primaryKey = "id_panier";
     public $timestamps = false;
     protected $fillable = ['id_client', 'montant', 'est_commande'];
}
