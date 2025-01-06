<?php

namespace App\Http\Controllers;

use App\Models\Adresse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Chauffeur;
use Illuminate\Support\Collection;

class AdresseController extends Controller
{
    public function show($id)
    {
        $address = Adresse::find($id);

        if ($address) {
            return response()->json([
                'rue' => $address->rue,
                'ville' => $address->ville,
                'cp' => $address->cp
            ]);
        } else {
            return response()->json(['error' => 'Address not found'], 404);
        }
    }

    public function traitement(Request $request)
    {
        $departement = strtoupper($request->input('departement')); // Convertir en majuscules
    
        // Transformation pour les départements corses
        if ($departement === '2A') {
            $resultat = 20;
        } elseif ($departement === '2B') {
            $resultat = 21;
        } elseif (is_numeric($departement) && $departement > 20) {
            $resultat = $departement + 1;
        } else {
            $resultat = $departement;
        }
    
        // Récupération des courses
        $courses = DB::table('course')
            ->join('adresse as depart', 'course.id_lieu_depart', '=', 'depart.id_adresse')
            ->join('adresse as arrivee', 'course.id_lieu_arrivee', '=', 'arrivee.id_adresse')
            ->where('course.terminee', '!=', 'true')
            ->where('depart.id_departement', '=', (int)$resultat)
            ->whereNull('course.id_chauffeur')
            ->whereNull('course.id_velo')
            ->select(
                'course.id_course',
                'course.prix_reservation',
                'course.date_prise_en_charge',
                'course.duree_course',
                'course.heure_arrivee',
                'course.terminee',
                'course.id_velo',
                'depart.ville as ville_depart',
                'depart.id_departement as code_dep',
                'arrivee.ville as ville_arrivee',
                'course.acceptee',
                'course.id_lieu_depart',
                'course.id_lieu_arrivee',
                'course.id_client'
            )
            ->orderBy('course.id_course', 'desc')
            ->paginate(5);
    
        // Récupération des chauffeurs avec leurs adresses
        $chauffeurs = Chauffeur::with('adresse')->get();
    
        // Filtrage des chauffeurs à moins de 30 km
        $filteredChauffeurs = new Collection();
        foreach ($courses as $course) {
            $courseCoordinates = $this->getCoordinatesFromAddress($course->ville_depart);
    
            if ($courseCoordinates) {
                foreach ($chauffeurs as $chauffeur) {
                    $chauffeurAddress = $chauffeur->adresse->rue . ', ' . $chauffeur->adresse->ville . ', ' . $chauffeur->adresse->cp;
                    $chauffeurCoordinates = $this->getCoordinatesFromAddress($chauffeurAddress);
    
                    if ($chauffeurCoordinates) {
                        $distance = $this->calculateDistance(
                            $courseCoordinates['lat'],
                            $courseCoordinates['lon'],
                            $chauffeurCoordinates['lat'],
                            $chauffeurCoordinates['lon']
                        );
    
                        if ($distance <= 42) { // Chauffeurs à moins de 30 km
                            $filteredChauffeurs->push($chauffeur);
                        }
                    }
                }
            }
        }
    
        return view('servicecourse/voircourse', compact('courses', 'chauffeurs', 'filteredChauffeurs'));
    }
    
    private function getCoordinatesFromAddress($address)
{
    $url = "https://nominatim.openstreetmap.org/search?q=" . urlencode($address) . "&format=json&limit=1";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'User-Agent: LaravelApp/1.0 (your-email@example.com)' // Remplace par ton email
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        $data = json_decode($response, true);
        if (!empty($data) && isset($data[0])) {
            return [
                'lat' => $data[0]['lat'],
                'lon' => $data[0]['lon']
            ];
        }
    }

    return null; // Retourne null si aucune donnée valide
}

    
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Rayon de la Terre en km
    
        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);
    
        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);
    
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
        return $earthRadius * $c; // Distance en km
    }
}
