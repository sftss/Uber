<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register'); // Retourner la vue d'inscription
    }

    // Autres méthodes comme store pour gérer l'enregistrement
}
