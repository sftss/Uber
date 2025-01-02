<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorairesRestaurant extends Model
{
    use HasFactory;

    protected $table = 'horaires_restaurant';
    protected $primaryKey = "id_horaires_restaurant";
    protected $fillable = ['id_jour', 'id_restaurant', 'horaires_ouverture', 'horaires_fermeture','ferme'];
    public $timestamps = false;

    public function jour()
    {
        return $this->belongsTo(Jour::class, 'id_jour', 'id_jour');
    }
}
