<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compose extends Model
{
    use HasFactory;
    protected $table = 'compose';

    // Définir la clé primaire composite
    protected $primaryKey = ['id_menu', 'id_produit'];

    public $incrementing = false;

    protected $fillable = [
        'id_menu',
        'id_produit',
    ];

    public $timestamps = false;
}
