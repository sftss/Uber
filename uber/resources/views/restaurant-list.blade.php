@extends('layouts.app')

@section('title', 'Bière')

@section('sidebar')
    @parent

@endsection

@section('content')
<h2>Les restaurants</h2>
<ul>
   @foreach ($restaurants as $restaurant)
       <li>{{ $restaurant->nom_etablissement }}</li>
  @endforeach
</ul>
@endsection