<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LieuVente extends Model
{
    protected $table = "LIEU_DE_VENTE_PF";
    protected $primaryKey = "ID_LIEU_DE_VENTE_PF";
    public $timestamps = false;
}
