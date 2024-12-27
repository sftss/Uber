<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstNote extends Model
{
    use HasFactory;

    protected $table = 'est_note';
    protected $primaryKey = "id_est_note";
    protected $fillable = ['id_note', 'id_chauffeur'];
    public $timestamps = false;

    public function note()
    {
        return $this->belongsTo(Note::class, 'id_note');
    }

    public function chauffeur()
    {
        return $this->belongsTo(Chauffeur::class, 'id_chauffeur');
    }
}
