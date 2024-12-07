<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        return view('course-list', ['courses' => Course::all()]);
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