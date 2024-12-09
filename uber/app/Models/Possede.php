<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Possede extends Model
{
    use HasFactory;

    protected $table = 'possede';

    // Définir la clé primaire composite
    protected $primaryKey = ['id_client', 'id_cb'];

    // Indiquer que la clé primaire composite n'est pas auto-incrémentée
    public $incrementing = false;

    protected $fillable = [
        'id_client',
        'id_cb',
    ];

    public $timestamps = false;
}
