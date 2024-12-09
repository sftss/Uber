<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CB extends Model
{
    use HasFactory;
    protected $table = 'cb';
    protected $primaryKey = "id_cb";

    public $timestamps = false;
    protected $fillable = [
        'num_cb',
        'nom_cb',
        'date_fin_validite',
        'type_cb',
    ];
}
