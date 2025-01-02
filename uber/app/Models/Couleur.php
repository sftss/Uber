<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Vehicule;

class Couleur extends Model
{
    use HasFactory;

    protected $table = "couleur";
    protected $primaryKey = "id_couleur";
    public $timestamps = false;
}
