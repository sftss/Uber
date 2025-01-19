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

        $clientId = Auth::user()->id_client;
        $courses = Course::with('chauffeur', 'lieuDepart', 'lieuArrivee')
            ->where('id_client', $clientId) 
            ->orderBy('id_course', 'desc')
            ->paginate(5);


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
            $tempCourse = DB::table('temp_course')->where('id_chauffeur', 7)->first();
        
            if ($tempCourse) {
                DB::table('course')
                ->where('id_course', $tempCourse->numcourse)
                ->update([
                    'id_chauffeur' => 7,
                    'acceptee' => true,
                ]);

        
                DB::statement('DROP TABLE IF EXISTS temp_course');
                
                return view('chauffeur/chauffeur-main');
            } else {
                return view('chauffeur/chauffeur-main');
            }
        } catch (\Exception $e) {
            return view('chauffeur/chauffeur-main');
        }
       
    }

    public function refuser($id) {
        DB::table('temp_course')
        ->where('id_chauffeur', 7)
        ->delete();

        return view('chauffeur/chauffeur-main');
    }

    public function update(Request $request, $id) {
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
        
        return redirect()->back()->with('success', 'Votre avis a été enregistré.');
    }

    public function generateInvoice($courseId) {
        $course = Course::findOrFail($courseId);
        return view('Facture', ['course' => $course]);
    }


    public function reserverCourse(Request $request) {
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
            'id_client' => 'required|integer',
        ]);

        try {
            $code_departement_depart = substr($data['lieu_depart_cp'], 0, 2);
            $code_departement_arrivee = substr($data['lieu_arrivee_cp'], 0, 2);

            $id_dep_depart = Departement::where('code_departement', (string)$code_departement_depart)->first();
            $id_dep_arrivee = Departement::where('code_departement', (string)$code_departement_arrivee)->first();

            if (!$id_dep_depart || !$id_dep_arrivee) {
                return response()->json(['status' => 'error', 'message' => 'Département non trouvé'], 400);
            }

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

            $heurescourse = floor($data['tempscourse'] / 3600);
            $minutescourse = floor(($data['tempscourse'] % 3600) / 60);
            $secondescourse = $data['tempscourse'] % 60;
            $duree_course = "{$heurescourse}:{$minutescourse}:{$secondescourse}";
            
            $course = Course::create([
                'chauffeur_nom' => $data['chauffeur_nom'],
                'chauffeur_prenom' => $data['chauffeur_prenom'],
                'id_lieu_depart' => $adresseDepart->id_adresse,
                'id_lieu_arrivee' => $adresseArrivee->id_adresse,
                'prix_reservation' => $data['prix_reservation'],
                'date_prise_en_charge' => substr($data['date_trajet'], 0, 10),
                'duree_course' => $duree_course,
                'terminee' => false,
                'id_client' => $data['id_client'],
            ]);

            return response()->json([
                'status' => 'success',
                'date' => substr($data['date_trajet'], 0, 10),
                'message' => 'Course réservée avec succès',
                'course' => $course,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur serveur: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function creercatCourse(Request $request) {
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
            'id_client' => 'required|integer',
        ]);

        try {
            $code_dep_depart = substr($validated['lieu_depart_cp'], 0, 2);
            $code_dep_arrivee = substr($validated['lieu_arrivee_cp'], 0, 2);

            $id_dep_depart = Departement::where('CODE_DEPARTEMENT', $code_dep_depart)->value('id');
            $id_dep_arrivee = Departement::where('CODE_DEPARTEMENT', $code_dep_arrivee)->value('id');

            if (!$id_dep_depart || !$id_dep_arrivee) {
                return response()->json(['status' => 'error', 'message' => 'Département non trouvé.'], 404);
            }

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

            $chauffeur = Chauffeur::whereHas('vehicule.categorie', function ($query) use ($validated) {
                $query->where('lib_categorie_vehicule', $validated['categorie']);
            })->first();

            if (!$chauffeur) {
                return response()->json(['status' => 'error', 'message' => 'Chauffeur non disponible.'], 404);
            }

            $tempscourse = $validated['tempscourse'];
            $duree_course = gmdate('H:i:s', $tempscourse);

            $course = Course::create([
                'id_chauffeur' => $chauffeur->id,
                'id_velo' => null,
                'id_lieu_depart' => $adresse_depart->id,
                'id_lieu_arrivee' => $adresse_arrivee->id,
                'id_client' => $validated['id_client'],
                'prix_reservation' => $validated['prix_reservation'],
                'date_prise_en_charge' => substr($validated['date_trajet'], 0, 10),
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

    public function modifierCourse(Request $request) {
        try {
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
                'id_client' => 'required|integer',
            ]);

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

            $code_departement_depart = substr($lieu_depart_cp, 0, 2);
            $code_departement_arrivee = substr($lieu_arrivee_cp, 0, 2);
            $heurescourse = floor($tempscourse / 3600);
            $minutescourse = floor(($tempscourse % 3600) / 60);
            $secondescourse = $tempscourse % 60;
            $duree_course = sprintf('%02d:%02d:%02d', $heurescourse, $minutescourse, $secondescourse);

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

            DB::table('course')
                ->where('id_course', $id_course)
                ->update([
                    'ID_CHAUFFEUR' => $id_chauffeur,
                    'ID_VELO' => null,
                    'ID_LIEU_DEPART' => $id_adresse_depart,
                    'ID_LIEU_ARRIVEE' => $id_adresse_arrivee,
                    'ID_CLIENT' => $validatedData['id_client'],
                    'PRIX_RESERVATION' => $prix_reservation,
                    'date_prise_en_charge' => substr($date_trajet['date_trajet'], 0, 10),
                    'DUREE_COURSE' => $duree_course,
                    'heure_arrivee' => null,
                    'TERMINEE' => false,
                    'acceptee' => null,
                ]);

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
