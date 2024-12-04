<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // Extension correcte de User
use Illuminate\Notifications\Notifiable;

class Client extends Authenticatable
{
    use HasFactory;
     protected $table = "client";
     protected $primaryKey = "id_client";
     public $timestamps = false;

     // Ajoutez ici toutes les colonnes qui peuvent être assignées en masse
    protected $fillable = [
        'id_client', 'id_sd', 'photo', 'tel_client', 'mail_client', 
        'num_siret', 'sexe_cp', 'prenom_cp', 'nom_cp', 'date_naissance_cp', 
        'est_particulier', 'mdp_client', 'newsletter'
    ];
}
