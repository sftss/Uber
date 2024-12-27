<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    use HasFactory;

    protected $table = "departement";
    protected $primaryKey = "id_departement";
    public $timestamps = false;

    public function adresses()
    {
        return $this->hasMany(Adresse::class, 'id_departement', 'id_departement');
    }
}
