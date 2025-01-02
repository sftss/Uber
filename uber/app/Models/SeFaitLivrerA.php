<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeFaitLivrerA extends Model
{
    protected $table = 'se_fait_livrer_a';
    protected $primaryKey = ['id_client', 'id_adresse'];
    public $incrementing = false;

    protected $fillable = [
        'id_client',
        'id_adresse',
    ];

    public $timestamps = false;
}
