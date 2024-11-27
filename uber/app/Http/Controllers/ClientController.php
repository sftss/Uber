<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use Illuminate\Http\Request;


class ClientController extends Controller
{
    public function index()
    {
        return view('client-list', ['clients' => Client::all()]);
    }

}