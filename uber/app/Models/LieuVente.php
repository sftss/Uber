<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LieuVente extends Model
{
    protected $table = "lieu_de_vente_pf";
    protected $primaryKey = "id_lieu_de_vente_pf";
    public $timestamps = false;
}
