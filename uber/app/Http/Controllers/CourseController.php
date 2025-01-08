<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\Note;
use App\Models\Departement;
use App\Models\Adresse;
use App\Models\EstNote;
use App\Models\Chauffeur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ChauffeurController;
use Illuminate\Support\Facades\Auth;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;


class CourseController extends Controller
{
    public function index() {
            //     $courses = DB::table("course")
            // ->join(
            //     "adresse as depart",
            //     "course.id_lieu_depart",
            //     "=",
            //     "depart.id_adresse"
            // )
            // ->join(
            //     "adresse as arrivee",
            //     "course.id_lieu_arrivee",
            //     "=",
            //     "arrivee.id_adresse"
            // )
            // ->join(
            //     "chauffeur as ch",
            //     "course.id_chauffeur",
            //     "=",
            //     "ch.id_chauffeur"
            // )
            // ->select(
            //     "course.id_course",
            //     "course.id_chauffeur",
            //     "course.prix_reservation",
            //     "course.date_prise_en_charge",
            //     "course.duree_course",
            //     "course.heure_arrivee",
            //     "course.terminee",
            //     "course.id_velo",
            //     "depart.ville as ville_depart",
            //     "ch.prenom_chauffeur",
            //     "ch.nom_chauffeur",
            //     "arrivee.ville as ville_arrivee",
            //     "course.acceptee"
            // )
            // ->orderBy("course.id_course", "desc") // Ordre décroissant pour afficher les plus récentes en premier
            // ->paginate(5);

        // $courses = Course::orderBy('id_course', "desc")->paginate(5);
        // ->orderBy("id_course", "desc") // Ordre décroissant pour afficher les plus récentes en premier
            // ->paginate(5);
        // ->get();

        // $clientId = Auth::id();
        // $courses = Course::with('chauffeur') 
        //     ->where('id_client', $clientId) 
        //     ->orderBy('id_course', 'desc')
        //     ->paginate(5);

        $clientId = Auth::user()->id_client;
        $courses = Course::with('chauffeur', 'lieuDepart', 'lieuArrivee')
            // ->where('id_client', $clientId) 
            ->orderBy('id_course', 'desc')
            ->paginate(5);

        // dd($courses);

        return view("course/course-list", ["courses" => $courses]);
    }

    public function destroy($id) {
        $course = Course::findOrFail($id);
        if ($course->terminee) {
            return redirect()
                ->route("courses.index")
                ->with(
                    "error",
                    "Cette course est terminée et ne peut pas être supprimée."
                );
        }
        $course->delete();
        return redirect()
            ->route("courses.index")
            ->with("success", "Course supprimée avec succès");
    }

    public function accepter($id) {
        try {
            // 1. Récupérer la ligne depuis `temp_course` où id_chauffeur = 7
            $tempCourse = DB::table('temp_course')->where('id_chauffeur', 7)->first();
        
            if ($tempCourse) {
                // 2. Insérer dans `course` avec `acceptee` à true
                DB::table('course')
                ->where('id_course', $tempCourse->numcourse)
                ->update([
                    'id_chauffeur' => 7,
                    'acceptee' => true,
                ]);

        
                // 3. Supprimer la table `temp_course`
                DB::statement('DROP TABLE IF EXISTS temp_course');
                
                return view('chauffeur/chauffeur-main');
            } else {
                return view('chauffeur/chauffeur-main');
            }
        } catch (\Exception $e) {
            return view('chauffeur/chauffeur-main');
        }
        /*
        $course = Course::findOrFail($id);
        $acceptee = "true";
        echo "<script>console.log(".$course.")</script>";

        
        //$course->update(["acceptee" => $acceptee]);
        \DB::table('course')
        ->where('id_course', $course->id_course)
        ->update([
            'acceptee' => $acceptee
        ]);
        
        echo "<script>console.log(".$course.")</script>";
        $chauffeurId = $course->id_chauffeur;

        echo $chauffeurId;

        $chauffeurController = new ChauffeurController();

        return $chauffeurController
            ->AfficherPropositions($chauffeurId)
            ->with("success", "Course acceptée");*/
    }

    public function refuser($id) {


        \DB::table('temp_course')
        ->where('id_chauffeur', 7)
        ->delete();


        return view('chauffeur/chauffeur-main');
/*
        $course = Course::findOrFail($id);
        $acceptee = "false";
        echo "<script>console.log(".$course.")</script>";

        
        //$course->update(["acceptee" => $acceptee]);
        \DB::table('course')
        ->where('id_course', $course->id_course)
        ->update([
            'acceptee' => $acceptee
        ]);
        
        
        
        echo "<script>console.log(".$course.")</script>";
        $chauffeurId = $course->id_chauffeur;

        echo $chauffeurId;

        $chauffeurController = new ChauffeurController();


        return $chauffeurController
            ->AfficherPropositions($chauffeurId)
            ->with("success", "Course acceptée");

            */
    }

    public function update(Request $request, $id) {
        // Validation des données
        $validated = $request->validate([
            'chauffeur' => 'nullable|string|max:255', 
            'depart' => 'required|string|max:255',
            'arrivee' => 'required|string|max:255',
            'prix' => 'required|numeric',
            'date' => 'required|date',
            'duree' => 'required|string|max:255',
            'temps' => 'nullable|date_format:H:i',
        ]);

        if ($request->chauffeur === 'Vélo') {
            $validated['chauffeur'] = null;  
        }

        $course = Course::findOrFail($id);
        $course->update([
            'id_chauffeur' => $validated['chauffeur'], 
            'id_lieu_depart' => $validated['depart'],
            'id_lieu_arrivee' => $validated['arrivee'],
            'prix_reservation' => $validated['prix'],
            'date_prise_en_charge' => $validated['date'],
            'duree_course' => $validated['duree'],
            'heure_arrivee' => $validated['temps'],
        ]);

        return response()->json(['success' => 'Course updated successfully']);
    }

    public function terminer(Request $request, $id_course) {
        $course = Course::findOrFail($id_course);
        $course->terminee = true;
        $course->save();

        return redirect()->back()->with('success', 'La course a été terminée. Vous pouvez maintenant donner votre avis.');
    }   
     
    public function submitReview(Request $request, $courseId) {
        $validated = $request->validate([
            'note' => 'required|integer|min:1|max:5',
            'pourboire' => 'required|numeric|min:0',
        ]);

        $note = $validated['note'];
        $pourboire = $validated['pourboire'];

        $course = Course::findOrFail($courseId);

        $course->pourboire = $pourboire;
        $course->save(); 

        $estnote = new EstNote();
        $estnote->id_note = $note;
        $estnote->id_chauffeur = $course->id_chauffeur;
        $estnote->save();

        $course->est_facture = true;
        $course->save();


        $test = session()->flash('review_submitted_' . $courseId, true);
        
        // dd($test);
        return redirect()->back()->with('success', 'Votre avis a été enregistré.');
    }

    public function generateInvoice($courseId) {
        $course = Course::findOrFail($courseId);
        return view('Facture', ['course' => $course]);
    }


    public function reserverCourse(Request $request)
    {
        // Validation des données envoyées par le frontend
        $data = $request->validate([
            'chauffeur_nom' => 'required|string',
            'chauffeur_prenom' => 'required|string',
            'lieu_depart_rue' => 'required|string',
            'lieu_depart_ville' => 'required|string',
            'lieu_depart_cp' => 'required|string',
            'lieu_arrivee_rue' => 'required|string',
            'lieu_arrivee_ville' => 'required|string',
            'lieu_arrivee_cp' => 'required|string',
            'prix_reservation' => 'required|numeric',
            'tempscourse' => 'required|numeric',
            'date_trajet' => 'required|date',
        ]);

        try {
            // Extraire les codes des départements à partir des codes postaux
            $code_departement_depart = substr($data['lieu_depart_cp'], 0, 2);
            $code_departement_arrivee = substr($data['lieu_arrivee_cp'], 0, 2);

            // Récupérer les ID des départements
            $id_dep_depart = Departement::where('code_departement', (string)$code_departement_depart)->first();
            $id_dep_arrivee = Departement::where('code_departement', (string)$code_departement_arrivee)->first();

            if (!$id_dep_depart || !$id_dep_arrivee) {
                return response()->json(['status' => 'error', 'message' => 'Département non trouvé'], 400);
            }

            // Insérer les adresses dans la base de données
            $adresseDepart = Adresse::create([
                'id_departement' => $id_dep_depart->id_departement,
                'rue' => $data['lieu_depart_rue'],
                'ville' => $data['lieu_depart_ville'],
                'cp' => $data['lieu_depart_cp'],
            ]);

            $adresseArrivee = Adresse::create([
                'id_departement' => $id_dep_arrivee->id_departement,
                'rue' => $data['lieu_arrivee_rue'],
                'ville' => $data['lieu_arrivee_ville'],
                'cp' => $data['lieu_arrivee_cp'],
            ]);

            // Calculer la durée de la course en heures, minutes, secondes
            $heurescourse = floor($data['tempscourse'] / 3600);
            $minutescourse = floor(($data['tempscourse'] % 3600) / 60);
            $secondescourse = $data['tempscourse'] % 60;
            $duree_course = "{$heurescourse}:{$minutescourse}:{$secondescourse}";

            // Insérer la course dans la table course
            $course = Course::create([
                'chauffeur_nom' => $data['chauffeur_nom'],
                'chauffeur_prenom' => $data['chauffeur_prenom'],
                'id_lieu_depart' => $adresseDepart->id_adresse,
                'id_lieu_arrivee' => $adresseArrivee->id_adresse,
                'prix_reservation' => $data['prix_reservation'],
                'date_trajet' => $data['date_trajet'],
                'duree_course' => $duree_course,
                'terminee' => false,
                'id_client' => 1,
            ]);

            // Retourner la réponse au client
            return response()->json([
                'status' => 'success',
                'message' => 'Course réservée avec succès',
                'course' => $course,
            ]);
        } catch (\Exception $e) {
            // Gestion des erreurs
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur serveur: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function creercatCourse(Request $request)
    {
        $validated = $request->validate([
            'categorie' => 'required|string',
            'lieu_depart_rue' => 'required|string',
            'lieu_depart_ville' => 'required|string',
            'lieu_depart_cp' => 'required|string',
            'lieu_arrivee_rue' => 'required|string',
            'lieu_arrivee_ville' => 'required|string',
            'lieu_arrivee_cp' => 'required|string',
            'prix_reservation' => 'nullable|numeric',
            'tempscourse' => 'nullable|integer',
            'date_trajet' => 'required|date',
        ]);

        try {
            // Extraire les codes départements
            $code_dep_depart = substr($validated['lieu_depart_cp'], 0, 2);
            $code_dep_arrivee = substr($validated['lieu_arrivee_cp'], 0, 2);

            // Récupérer les IDs des départements
            $id_dep_depart = Departement::where('CODE_DEPARTEMENT', $code_dep_depart)->value('id');
            $id_dep_arrivee = Departement::where('CODE_DEPARTEMENT', $code_dep_arrivee)->value('id');

            if (!$id_dep_depart || !$id_dep_arrivee) {
                return response()->json(['status' => 'error', 'message' => 'Département non trouvé.'], 404);
            }

            // Créer ou récupérer les adresses
            $adresse_depart = Adresse::create([
                'id_departement' => $id_dep_depart,
                'rue' => $validated['lieu_depart_rue'],
                'ville' => $validated['lieu_depart_ville'],
                'cp' => $validated['lieu_depart_cp'],
            ]);

            $adresse_arrivee = Adresse::create([
                'id_departement' => $id_dep_arrivee,
                'rue' => $validated['lieu_arrivee_rue'],
                'ville' => $validated['lieu_arrivee_ville'],
                'cp' => $validated['lieu_arrivee_cp'],
            ]);

            // Récupérer un chauffeur selon la catégorie
            $chauffeur = Chauffeur::whereHas('vehicule.categorie', function ($query) use ($validated) {
                $query->where('lib_categorie_vehicule', $validated['categorie']);
            })->first();

            if (!$chauffeur) {
                return response()->json(['status' => 'error', 'message' => 'Chauffeur non disponible.'], 404);
            }

            // Calculer la durée
            $tempscourse = $validated['tempscourse'];
            $duree_course = gmdate('H:i:s', $tempscourse);

            // Créer la course
            $course = Course::create([
                'id_chauffeur' => $chauffeur->id,
                'id_velo' => null,
                'id_lieu_depart' => $adresse_depart->id,
                'id_lieu_arrivee' => $adresse_arrivee->id,
                'id_client' => 1, // ID client exemple
                'prix_reservation' => $validated['prix_reservation'],
                'date_prise_en_charge' => $validated['date_trajet'],
                'duree_course' => $duree_course,
                'terminee' => false,
                'id_client' => 1,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Course créée avec succès.',
                'course' => $course,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la création de la course.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function modifierCourse(Request $request)
    {
        try {
            // Valider les données entrantes
            $validatedData = $request->validate([
                'categorie' => 'required|string',
                'lieu_depart_rue' => 'required|string',
                'lieu_depart_ville' => 'required|string',
                'lieu_depart_cp' => 'required|string',
                'lieu_arrivee_rue' => 'required|string',
                'lieu_arrivee_ville' => 'required|string',
                'lieu_arrivee_cp' => 'required|string',
                'prix_reservation' => 'nullable|numeric',
                'tempscourse' => 'nullable|integer',
                'date_trajet' => 'required|date',
                'id_course' => 'required|integer',
            ]);

            // Extraire les données validées
            $categorie = $validatedData['categorie'];
            $lieu_depart_rue = $validatedData['lieu_depart_rue'];
            $lieu_depart_ville = $validatedData['lieu_depart_ville'];
            $lieu_depart_cp = $validatedData['lieu_depart_cp'];
            $lieu_arrivee_rue = $validatedData['lieu_arrivee_rue'];
            $lieu_arrivee_ville = $validatedData['lieu_arrivee_ville'];
            $lieu_arrivee_cp = $validatedData['lieu_arrivee_cp'];
            $prix_reservation = $validatedData['prix_reservation'];
            $tempscourse = $validatedData['tempscourse'];
            $date_trajet = $validatedData['date_trajet'];
            $id_course = $validatedData['id_course'];

            // Calculs supplémentaires
            $code_departement_depart = substr($lieu_depart_cp, 0, 2);
            $code_departement_arrivee = substr($lieu_arrivee_cp, 0, 2);
            $heurescourse = floor($tempscourse / 3600);
            $minutescourse = floor(($tempscourse % 3600) / 60);
            $secondescourse = $tempscourse % 60;
            $duree_course = sprintf('%02d:%02d:%02d', $heurescourse, $minutescourse, $secondescourse);

            // Rechercher les départements
            $id_dep_depart = DB::table('departement')
                ->where('CODE_DEPARTEMENT', $code_departement_depart)
                ->value('id_departement');
            $id_dep_arrivee = DB::table('departement')
                ->where('CODE_DEPARTEMENT', $code_departement_arrivee)
                ->value('id_departement');

            if (!$id_dep_depart || !$id_dep_arrivee) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Code département non trouvé',
                ], 404);
            }

            // Insérer les adresses
            $id_adresse_depart = DB::table('adresse')->insertGetId([
                'id_departement' => $id_dep_depart,
                'rue' => $lieu_depart_rue,
                'ville' => $lieu_depart_ville,
                'cp' => $lieu_depart_cp,
            ]);
            $id_adresse_arrivee = DB::table('adresse')->insertGetId([
                'id_departement' => $id_dep_arrivee,
                'rue' => $lieu_arrivee_rue,
                'ville' => $lieu_arrivee_ville,
                'cp' => $lieu_arrivee_cp,
            ]);

            // Récupérer un chauffeur
            $id_chauffeur = DB::table('chauffeur')
                ->join('vehicule', 'chauffeur.id_chauffeur', '=', 'vehicule.id_chauffeur')
                ->join('categorie_vehicule', 'vehicule.id_categorie_vehicule', '=', 'categorie_vehicule.id_categorie_vehicule')
                ->where('categorie_vehicule.lib_categorie_vehicule', $categorie)
                ->value('chauffeur.id_chauffeur');

            if (!$id_chauffeur) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Chauffeur non trouvé pour cette catégorie',
                ], 404);
            }

            // Mise à jour de la course
            DB::table('course')
                ->where('id_course', $id_course)
                ->update([
                    'ID_CHAUFFEUR' => $id_chauffeur,
                    'ID_VELO' => null,
                    'ID_LIEU_DEPART' => $id_adresse_depart,
                    'ID_LIEU_ARRIVEE' => $id_adresse_arrivee,
                    'ID_CLIENT' => 1,
                    'PRIX_RESERVATION' => $prix_reservation,
                    'DATE_PRISE_EN_CHARGE' => $date_trajet,
                    'DUREE_COURSE' => $duree_course,
                    'heure_arrivee' => null,
                    'TERMINEE' => false,
                    'acceptee' => null,
                ]);

            // Répondre au client
            return response()->json([
                'status' => 'success',
                'message' => 'Course modifiée avec succès',
                'details' => [
                    'chauffeur' => $id_chauffeur,
                    'depart' => $id_adresse_depart,
                    'arrivee' => $id_adresse_arrivee,
                    'prix_reservation' => $prix_reservation,
                    'date_trajet' => $date_trajet,
                    'duree_course' => $duree_course,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la modification de la course',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

}
