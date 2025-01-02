<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstContenuPlat extends Model
{
    use HasFactory;
    protected $table = 'est_contenu_dans_plat';
    protected $primaryKey = ['id_plat', 'id_commande_repas'];
    public $incrementing = false;
    protected $fillable = [
        'id_plat',
        'id_commande_repas',
        'quantite',
    ];

    public $timestamps = false;
}
