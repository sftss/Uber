@extends('layouts.header')

<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />

<div class="container containerVerif">
    <h1>Vérification de votre compte</h1>
    <form style="display: flex;flex-direction: column;margin: 1% 0 4% 0;" action="{{ route('verification.submit') }}"
        method="POST">
        @csrf
        <div class="form-group form-groupVerif">
            <label for="code">Code de vérification</label>
            <input type="text" name="code" id="code" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary butVerifier">Vérifier</button>
    </form>
</div>
