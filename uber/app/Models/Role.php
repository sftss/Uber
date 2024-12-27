<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secteur extends Model
{
    use HasFactory;
    protected $table = 'role';
    protected $primaryKey = 'id_role';  
        protected $fillable = [
        'lib_role',
    ];
    public $timestamps = false;

    public function clients()
    {
        return $this->hasMany(Client::class, 'id_role', 'id_role');
    }
}
