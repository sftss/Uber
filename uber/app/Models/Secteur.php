<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secteur extends Model
{
    use HasFactory;
    protected $table = 'secteur_d_activite';
    protected $primaryKey = 'id_sd';
    protected $fillable = ['lib_sd'];
    public $timestamps = false;
}
