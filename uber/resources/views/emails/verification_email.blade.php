@extends('layouts.header')

<p>Bonjour {{ $prenom }} {{ $nom }},</p>
<p>Merci pour votre inscription. Veuillez utiliser le code suivant pour vérifier votre compte :</p>
<h3>{{ $code }}</h3>
<p>Entrez ce code sur la page de vérification pour activer votre compte.</p>
<p>Merci de nous faire confiance,</p>
<p>L'équipe de notre plateforme.</p>
