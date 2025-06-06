<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Possede extends Model
{
    use HasFactory;

    protected $table = 'possede';
    protected $primaryKey = ['id_client', 'id_cb'];
    public $incrementing = false;

    protected $fillable = [
        'id_client',
        'id_cb',
    ];

    public $timestamps = false;

    public function client() {
        return $this->belongsTo(Client::class, 'id_client');
    }

    public function carte() {
        return $this->belongsTo(CB::class, 'id_cb');
    }

}
