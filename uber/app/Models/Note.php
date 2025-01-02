<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $table = "note";
    protected $primaryKey = "id_note";
    protected $fillable = ['valeur_note'];

    public $timestamps = false;
}
