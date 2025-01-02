<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstContenuDansMenu extends Model
{
    use HasFactory;
    protected $table = 'est_contenu_dans_menu';
    protected $primaryKey = ['id_menu', 'id_commande_repas'];
    public $incrementing = false;

    protected $fillable = [
        'id_menu',
        'id_commande_repas',
        'quantite',
    ];

    public $timestamps = false;
}
