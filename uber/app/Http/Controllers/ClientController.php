<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    public function index(){
        return view ("clients-list", ['clients'=>client::all()]);
    }
}
