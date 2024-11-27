@extends('layouts.app')

@section('title', 'Liste des Clients')

@section('content')
    <h2>Les clients</h2>
    <ul>
        @foreach ($clients as $client)
            <li>{{ $client->nom_cp }}</li>
        @endforeach
    </ul>
@endsection
