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
        $id = auth()->check() ? auth()->user()->id_client : 1;

        return view("course/map", [
            'chauffeurs' => $chauffeurs,
            'categories' => $categories,
            'coursePourModification' => $coursePourModification,
            'id' => $id
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

    public function createTempCourses(Request $request)
    {
        try {
            // Récupérer les données envoyées
            $data = $request->all();

            // Vérifier si l'opération demandée est "read_temp"
            if (isset($data['operation']) && $data['operation'] == 'read_temp') {
                // Lire les données de la table temporaire
                $courses = DB::table('temp_course')->get();

                return response()->json([
                    'status' => 'success',
                    'data' => $courses,
                ]);
            }

            // Vérifier si le tableau de chauffeurs est présent
            if (!isset($data['chauffeurtab']) || !is_array($data['chauffeurtab'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Aucun chauffeur fourni.',
                ], 400);
            }

            // Créer la table temporaire si elle n'existe pas encore
            DB::statement("DROP TABLE IF EXISTS temp_course");

            DB::statement("
                CREATE TABLE temp_course (
                    id_course SERIAL PRIMARY KEY,
                    id_chauffeur INT,
                    id_velo INT,
                    id_lieu_depart INT,
                    id_lieu_arrivee INT,
                    id_client INT,
                    prix_reservation NUMERIC(6,2),
                    date_prise_en_charge DATE,
                    duree_course TIME,
                    heure_arrivee TIME,
                    terminee BOOL,
                    acceptee BOOL,
                    validation_client BOOL,
                    validation_chauffeur BOOL,
                    pourboire NUMERIC(6,2),
                    est_facture BOOL,
                    numcourse INT
                )
            ");

            // Parcourir chaque chauffeur et créer une course
            foreach ($data['chauffeurtab'] as $chauffeur) {
                $id_chauffeur = $chauffeur['id_chauffeur'] ?? null;

                if (!$id_chauffeur) {
                    // Si l'id du chauffeur est manquant, passer au suivant
                    continue;
                }

                DB::table('temp_course')->insert([
                    'id_chauffeur' => $id_chauffeur,
                    'id_velo' => $data['id_velo'] ?? null,
                    'id_lieu_depart' => $data['id_lieu_depart'] ?? null,
                    'id_lieu_arrivee' => $data['id_lieu_arrivee'] ?? null,
                    'id_client' => $data['id_client'] ?? 1, // Par défaut, client ID = 1
                    'prix_reservation' => $data['prix_reservation'] ?? null,
                    'date_prise_en_charge' => $data['date_prise_en_charge'] ?? null,
                    'duree_course' => $data['duree_course'] ?? null,
                    'heure_arrivee' => null,
                    'terminee' => false,
                    'numcourse' => $data['id_course'] ?? null,
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Courses créées avec succès pour tous les chauffeurs.',
            ]);
        } catch (\Exception $e) {
            // Gestion des erreurs

            return response()->json([
                'status' => 'error',
                'message' => 'Une erreur est survenue: ' . $e->getMessage(),
            ], 500);
        }
    }
}
