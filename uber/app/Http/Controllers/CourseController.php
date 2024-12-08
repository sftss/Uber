<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CourseController extends Controller
{
    public function index()
    {
        
        $courses = DB::table('course')
        ->join('adresse as depart', 'course.id_lieu_depart', '=', 'depart.id_adresse')
        ->join('adresse as arrivee', 'course.id_lieu_arrivee', '=', 'arrivee.id_adresse')
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
            'arrivee.ville as ville_arrivee'
        )
        ->get();
    
    return view('course-list', ['courses' => $courses]);
    



    }

    



    public function destroy($id)
    {
        // Récupérer la course par son ID
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

}