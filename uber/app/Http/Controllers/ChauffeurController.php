<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chauffeur;
use App\Models\CategorieVehicule;
use App\Models\Couleur;
use App\Models\Vehicule;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

class ChauffeurController extends Controller
{
    public function index($id = null) {
        $chauffeurs = Chauffeur::with('adresse','vehicule.categorieVehicule','vehicule.couleur','adresse.departement')->get();
        $categories = CategorieVehicule::all();
        
        $coursePourModification = null;
        if ($id) {
            $coursePourModification = Course::with(['lieuDepart', 'lieuArrivee'])->findOrFail($id);
        }

        return view("course/map", [
            'chauffeurs' => $chauffeurs,
            'categories' => $categories,
            'coursePourModification' => $coursePourModification
        ]);
    }

    public function AfficherPropositions($id) {

        $tableExists = DB::select("SELECT to_regclass('s_uber.temp_course')");

    if (!$tableExists[0]->to_regclass) {
        return view('chauffeur/chauffeur-propositions', ['courses' => collect()]);
    }

        $courses = DB::table('temp_course')
            ->join('adresse as depart', 'temp_course.id_lieu_depart', '=', 'depart.id_adresse')
            ->join('adresse as arrivee', 'temp_course.id_lieu_arrivee', '=', 'arrivee.id_adresse')
            ->join('chauffeur as ch','temp_course.id_chauffeur','=','ch.id_chauffeur')
            ->where('ch.id_chauffeur', '=', $id)
            ->where('temp_course.terminee','!=','true')
            ->select(
                'temp_course.id_course',
                'temp_course.id_chauffeur',
                'temp_course.prix_reservation',
                'temp_course.date_prise_en_charge',
                'temp_course.duree_course',
                'temp_course.heure_arrivee',
                'temp_course.terminee',
                'temp_course.id_velo',
                'depart.ville as ville_depart',
                'ch.prenom_chauffeur',
                'ch.nom_chauffeur',
                'arrivee.ville as ville_arrivee',
                'temp_course.acceptee'
            )
            ->orderBy('temp_course.id_course', 'desc')
            ->paginate(5);    

        return view('chauffeur/chauffeur-propositions', ['courses' => $courses]);
    }

    public function AfficherCoursesPassees($id) {
        $courses = DB::table('course')
            ->join('adresse as depart', 'course.id_lieu_depart', '=', 'depart.id_adresse')
            ->join('adresse as arrivee', 'course.id_lieu_arrivee', '=', 'arrivee.id_adresse')
            ->join('chauffeur as ch','course.id_chauffeur','=','ch.id_chauffeur')
            ->where('ch.id_chauffeur', '=', $id)
            ->where('course.terminee','=','true')
            ->select(
                'course.id_course',
                'course.id_chauffeur',
                'course.prix_reservation',
                'course.date_prise_en_charge',
                'course.duree_course',
                'course.heure_arrivee',
                'course.terminee',
                'course.id_velo',
                'depart.ville as ville_depart',
                'ch.prenom_chauffeur',
                'ch.nom_chauffeur',
                'arrivee.ville as ville_arrivee',
                'course.acceptee'
            )
            ->orderBy('course.id_course', 'desc')
            ->paginate(5);    

        return view('chauffeur/chauffeur-propositions', ['courses' => $courses]);
    }

    public function AfficherCoursesAVenir($id) {
        $courses = DB::table('course')
            ->join('adresse as depart', 'course.id_lieu_depart', '=', 'depart.id_adresse')
            ->join('adresse as arrivee', 'course.id_lieu_arrivee', '=', 'arrivee.id_adresse')
            ->join('chauffeur as ch','course.id_chauffeur','=','ch.id_chauffeur')
            ->where('ch.id_chauffeur', '=', $id)
            ->where('course.terminee','!=','true')
            ->select(
                'course.id_course',
                'course.id_chauffeur',
                'course.prix_reservation',
                'course.date_prise_en_charge',
                'course.duree_course',
                'course.heure_arrivee',
                'course.terminee',
                'course.id_velo',
                'depart.ville as ville_depart',
                'ch.prenom_chauffeur',
                'ch.nom_chauffeur',
                'arrivee.ville as ville_arrivee',
                'course.acceptee',
                'course.validationchauffeur'
            )
            ->orderBy('course.id_course', 'desc')
            ->paginate(5);    

        return view('chauffeur/chauffeur-a-venir', ['courses' => $courses]);
    }

    public function terminer($id) {
        $course = Course::findOrFail($id);
        $terminee = "true";
        $validationClient= DB::table('course')
                    ->where('id_course', $course->id_course)
                    ->value('validationclient'); 

        
        DB::table('course')
        ->where('id_course', $course->id_course)
        ->update([
            'validationchauffeur' => $terminee
        ]);

        if (json_encode($validationClient) == "true") {
                DB::table('course')
            ->where('id_course', $course->id_course)
            ->update([
                'terminee' => $terminee
            ]);
        }

        $chauffeurId = $course->id_chauffeur;

        echo $chauffeurId;

        $chauffeurController = new ChauffeurController();

        return $chauffeurController
            ->AfficherCoursesAVenir($chauffeurId)
            ->with("success", "Course terminée");
    }

    public function trouverChauffeurs(){
        $chauffeurs = DB::table('chauffeur as ch')
        ->where('ch.validerrh', '!=', true) 
        ->select(
            'ch.id_chauffeur',
            'ch.nom_chauffeur',
            'ch.prenom_chauffeur',
            'ch.date_naissance_chauffeur',
            'ch.sexe_chauffeur',
            'ch.tel_chauffeur',
            'ch.mail_chauffeur',
            'ch.nom_entreprise',
            'ch.num_siret',
            'ch.validerrh',
            'ch.daterdvrh'
        )
        ->get();

    
        return view('rh/voirchauffeur', compact('chauffeurs'));
    }

    public function planifierRdv(Request $request,$chauffeur_id)
    {
        $chauffeur = Chauffeur::findOrFail($chauffeur_id);
        $chauffeur->daterdvrh = $request->input('rdv_date');
        $chauffeur->save();

        return redirect()->back()->with('success', 'Rendez-vous planifié avec succès.');
    }

    public function changerStatutRdv($chauffeur_id, Request $request)
    {
        // Trouver le chauffeur par ID
        $chauffeur = Chauffeur::findOrFail($chauffeur_id);
        
        // Si l'action est "accepter", mettre à jour validerrh à true
        if ($request->input('statut') == 'accepter') {
            $chauffeur->validerrh = true; // Marquer comme accepté
            $chauffeur->save();
        } elseif ($request->input('statut') == 'refuser') {
            // Si l'action est "refuser", supprimer le chauffeur de la base de données
            $chauffeur->delete();
        }
        
        // Rediriger vers la page précédente avec un message de succès
        return redirect()->back()->with('success', $request->input('statut') == 'accepter' ? 'Rendez-vous accepté.' : 'Chauffeur supprimé.');
    }
}
