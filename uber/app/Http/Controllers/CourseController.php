<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\Note;
use App\Models\EstNote;
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
}
