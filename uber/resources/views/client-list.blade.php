@extends('layouts.app')

@section('title', 'Bière')

@section('sidebar')
    @parent

@endsection

@section('content')
<h2>Les clients</h2>
<ul>
   @foreach ($clients as $client)
       <li>{{ $client->nom_cp }}</li>
  @endforeach
</ul>
@endsection