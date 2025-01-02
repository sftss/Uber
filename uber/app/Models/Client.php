<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Role;

class Client extends Authenticatable
{
    use HasFactory;
    protected $table = "client";
    protected $primaryKey = "id_client";
    public $timestamps = false;

    protected $fillable = [
        'id_client', 'id_sd', 'photo', 'tel_client', 'mail_client', 
        'num_siret', 'sexe_cp', 'prenom_cp', 'nom_cp', 'date_naissance_cp', 
        'est_particulier', 'mdp_client', 'newsletter','code_verif','est_verif','id_role','latitude','longitude'
    ];
        
    // public function role() {
    //     return $this->belongsTo(Role::class, 'id_role', 'id_role');
    // }

    public function getPhotoAttribute($value) {
        return $value && !str_starts_with($value, 'http') ? asset('storage/' . $value) : $value;
    }
}
