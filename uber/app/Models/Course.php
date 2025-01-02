<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Chauffeur;
use App\Models\Adresse;
use App\Models\Client;
use App\Models\Facture;


class Course extends Model
{
    use HasFactory;

    protected $table = "course";
    protected $primaryKey = "id_course";
    protected $fillable = [
        'id_course','id_chauffeur','id_lieu_depart','id_lieu_arrivee','id_client','prix_reservation','date_prise_en_charge','duree_course','heure_arrivee','id_velo','terminee',"acceptee","validationclient","validationchauffeur","pourboire","est_facture",
    ];

    public $timestamps = false;

    public function index() {
        $courses = Course::all();
        return view('courses.index', compact('courses'));
    }

    public function lieuDepart() {
        return $this->belongsTo(Adresse::class, 'id_lieu_depart', 'id_adresse');
    }

    public function lieuArrivee() {
        return $this->belongsTo(Adresse::class, 'id_lieu_arrivee', 'id_adresse');
    }

    public function chauffeur() {
        return $this->belongsTo(Chauffeur::class, 'id_chauffeur', 'id_chauffeur');
    }

    public function client() {
        return $this->belongsTo(Client::class, 'id_client', 'id_client');
    }

    public function facture() {
        return $this->hasOne(Facture::class, 'id_course', 'id_course');
    }
}
