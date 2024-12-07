@extends('layouts.header')

<div class="container">
    <h1>Vérification de votre compte</h1>
    <form action="{{ route('verification.submit') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="code">Code de vérification</label>
            <input type="text" name="code" id="code" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Vérifier</button>
    </form>
</div>

