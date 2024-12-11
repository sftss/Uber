<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ChauffeurController;

class CourseController extends Controller
{
    public function index()
    {
        $courses = DB::table("course")
            ->join(
                "adresse as depart",
                "course.id_lieu_depart",
                "=",
                "depart.id_adresse"
            )
            ->join(
                "adresse as arrivee",
                "course.id_lieu_arrivee",
                "=",
                "arrivee.id_adresse"
            )
            ->join(
                "chauffeur as ch",
                "course.id_chauffeur",
                "=",
                "ch.id_chauffeur"
            )
            ->select(
                "course.id_course",
                "course.id_chauffeur",
                "course.prix_reservation",
                "course.date_prise_en_charge",
                "course.duree_course",
                "course.heure_arrivee",
                "course.terminee",
                "course.id_velo",
                "depart.ville as ville_depart",
                "ch.prenom_chauffeur",
                "ch.nom_chauffeur",
                "arrivee.ville as ville_arrivee",
                "course.acceptee"
            )
            ->orderBy("course.id_course", "desc") // Ordre décroissant pour afficher les plus récentes en premier
            ->paginate(5);
        // ->get();
        return view("course-list", ["courses" => $courses]);
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        // Vérifier si la course est terminée
        if ($course->terminee) {
            // La course est terminée, ne pas la supprimer et afficher un message d'erreur
            return redirect()
                ->route("courses.index")
                ->with(
                    "error",
                    "Cette course est terminée et ne peut pas être supprimée."
                );
        }
        // Supprimer la course
        $course->delete();
        // Rediriger avec un message de succès
        return redirect()
            ->route("courses.index")
            ->with("success", "Course supprimée avec succès");
    }

    public function accepter($id)
    {
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

    public function terminate($id)
    {
        $course = Course::findOrFail($id);

        if ($course->terminee) {
            return redirect()->route('courses.index')->with('error', 'Cette course est déjà terminée.');
        }

        $course->terminee = true;
        $course->save();

        return redirect()->route('courses.index')->with('success', 'Course terminée avec succès.');
    }
    
    public function addReview(Request $request, $id)
    {
        $request->validate([
            'note' => 'required|integer|min:1|max:5',
            'avis' => 'nullable|string|max:500',
            'pourboire' => 'nullable|numeric|min:0'
        ]);

        $course = Course::findOrFail($id);

        if (!$course->terminee) {
            return response()->json(['error' => 'La course n\'est pas encore terminée.'], 400);
        }

        $course->note = $request->input('note');
        $course->avis = $request->input('avis');
        
        // If you want to add a tip functionality
        if ($request->has('pourboire')) {
            $course->pourboire = $request->input('pourboire');
        }

        $course->save();

        return response()->json(['success' => 'Avis et note ajoutés avec succès.']);
    }
}
