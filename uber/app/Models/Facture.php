<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    protected $table = 'facture';
    protected $primaryKey = 'id_facture';
    public $timestamps = false;

    protected $fillable = [
        'id_course',
        'montant_facture',
        'description_facture',
        'pourboire',
        'date_course',
        'taux_tva',
        'numero_mois',
        'est_particulier',
    ];
}
