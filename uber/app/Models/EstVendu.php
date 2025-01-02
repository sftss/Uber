<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstVendu extends Model
{
    use HasFactory;

    protected $table = 'est_vendu';
    public $timestamps = false;
    protected $primaryKey = ['id_lieu_de_vente_pf', 'id_produit'];
    public $incrementing = false;

    protected $fillable = [
        'id_lieu_de_vente_pf',
        'id_produit',
    ];
}
