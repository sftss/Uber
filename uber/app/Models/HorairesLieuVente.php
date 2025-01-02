<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorairesLieuVente extends Model
{
    use HasFactory;
    protected $table = "horaires_lieu_vente";
    protected $primaryKey = "id_horaires_lieu_vente";
    protected $fillable = [
        'id_jour',
        'id_lieu_de_vente_pf',
        'horaires_ouverture', 
        'horaires_fermeture',
        'ferme',
    ];
    public $timestamps = false;

    public function jour()
    {
        return $this->belongsTo(Jour::class, 'id_jour', 'id_jour');
    }
}
