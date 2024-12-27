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

        $courses = Course::orderBy('id_course', "desc")->paginate(5);
        // ->orderBy("id_course", "desc") // Ordre décroissant pour afficher les plus récentes en premier
            // ->paginate(5);
        // ->get();
        // dd($courses);

        return view("course-list", ["courses" => $courses]);
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
            ->with("success", "Course acceptée");
    }

    public function refuser($id) {
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
    }

    public function update(Request $request, $id) {
        // Validation des données
        $validated = $request->validate([
            'chauffeur' => 'nullable|string|max:255',   // Permet d'être vide si non renseigné
            'depart' => 'required|string|max:255',
            'arrivee' => 'required|string|max:255',
            'prix' => 'required|numeric',
            'date' => 'required|date',
            'duree' => 'required|string|max:255',
            'temps' => 'nullable|date_format:H:i',
        ]);

        if ($request->chauffeur === 'Vélo') {
            $validated['chauffeur'] = null;  // Mettre chauffeur à null si vélo
        }
        // Récupérer la course et mettre à jour les données
        $course = Course::findOrFail($id);
        $course->update([
            'id_chauffeur' => $validated['chauffeur'],  // Utilisation de id_chauffeur pour la mise à jour
            'id_lieu_depart' => $validated['depart'],
            'id_lieu_arrivee' => $validated['arrivee'],
            'prix_reservation' => $validated['prix'],
            'date_prise_en_charge' => $validated['date'],
            'duree_course' => $validated['duree'],
            'heure_arrivee' => $validated['temps'],
        ]);

        return response()->json(['success' => 'Course updated successfully']);
    }

    public function terminer(Request $request, $id_course)
    {
        // Mettre à jour le statut de la course
        $course = Course::findOrFail($id_course);
        $course->terminee = true;
        $course->save();

        // Ajouter un message de confirmation (facultatif)
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

        $course->est_facture = true; // Marquer la course comme facturée
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
