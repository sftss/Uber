@extends('layouts.app')

@section('title', 'Uber course')

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
