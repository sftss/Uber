<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Models\Departement;

class Adresse extends Model
{
    use HasFactory;
    protected $table = "adresse";
    protected $primaryKey = "id_adresse";
    public $timestamps = false;

    public function departement(): HasOne{
        return $this->hasOne(
            Departement::class, 
            "id_departement", 
            "id_departement");
    }

    protected $fillable = [
         'id_adresse','id_departement', 'rue', 'ville', 'cp'
    ];

}
