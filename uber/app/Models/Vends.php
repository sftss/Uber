<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vends extends Model
{
    use HasFactory;
    protected $table = 'vends';
    protected $primaryKey = ['id_restaurant', 'id_produit'];
    public $incrementing = false;

    protected $fillable = [
        'id_restaurant',
        'id_produit',
    ];

    public $timestamps = false;
}
