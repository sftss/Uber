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

    public function update(Request $request, $id)
{
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

    // Vérifier si le chauffeur est un vélo
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