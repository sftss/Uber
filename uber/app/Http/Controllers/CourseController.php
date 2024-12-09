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
        $courses = DB::table('course')
        ->join('adresse as depart', 'course.id_lieu_depart', '=', 'depart.id_adresse')
        ->join('adresse as arrivee', 'course.id_lieu_arrivee', '=', 'arrivee.id_adresse')
        ->join('chauffeur as ch','course.id_chauffeur','=','ch.id_chauffeur')
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
        ->orderBy('course.id_course', 'desc') // Ordre décroissant pour afficher les plus récentes en premier
        ->paginate(5);    
        // ->get();
    return view('course-list', ['courses' => $courses]);
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
         // Vérifier si la course est terminée
         if ($course->terminee) {
            // La course est terminée, ne pas la supprimer et afficher un message d'erreur
            return redirect()->route('courses.index')->with('error', 'Cette course est terminée et ne peut pas être supprimée.');
        }
        // Supprimer la course
        $course->delete();
        // Rediriger avec un message de succès
        return redirect()->route('courses.index')->with('success', 'Course supprimée avec succès');
    }
    public function accepter($id)
    {
        $course = Course::findOrFail($id);
        $course->update(['acceptee' => true]); //marche pas

        $chauffeurId = $course->id_chauffeur;

        echo($chauffeurId);

        $chauffeurController = new ChauffeurController();

        return $chauffeurController->AfficherPropositions($chauffeurId)->with('success', 'Course acceptée');
    }
}