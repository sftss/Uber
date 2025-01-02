<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jour extends Model
{
    use HasFactory;

    protected $table = 'jour'; 
    protected $primaryKey = 'id_jour'; 
    protected $fillable = ['lib_jour'];

    public $timestamps = false; 
}
