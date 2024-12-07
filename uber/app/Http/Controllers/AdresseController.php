<?php

namespace App\Http\Controllers;

use App\Models\Adresse;
use Illuminate\Http\Request;

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
}
