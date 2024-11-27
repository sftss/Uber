<link href="{{ asset('style/app.css') }}" rel="stylesheet">

@extends('layouts.app')

@section('title', 'Uber course')

@section('nav')
<ul>
    <li><a href="{{ route('restaurants.index') }}">Restaurants</a></li>
    <li><a href="{{ route('clients.index') }}">Clients</a></li>
</ul>
@show

@section('sidebar')
    @parent

@endsection

@section('content')
<h2>Les courses</h2>
<ul>
   @foreach ($courses as $course)
       <li>{{ $course->temps_arrivee }}</li>
  @endforeach
</ul>
@endsection
<a href="{{ url("/") }}">
    <p>arrière</p>
</a>
