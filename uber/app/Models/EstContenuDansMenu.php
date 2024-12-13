<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstContenuDansMenu extends Model
{
    use HasFactory;
    protected $table = 'est_contenu_dans_menu';

    // Définir la clé primaire composite
    protected $primaryKey = ['id_menu', 'id_commande_repas'];

    // Indiquer que la clé primaire composite n'est pas auto-incrémentée
    public $incrementing = false;

    protected $fillable = [
        'id_menu',
        'id_commande_repas',
        'quantite',
    ];

    public $timestamps = false;
}
