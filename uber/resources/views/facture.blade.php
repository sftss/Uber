@extends('layouts.header')

<meta content="{{ csrf_token() }}" name="csrf-token">
<link href="{{ URL::asset('assets/style/course.css') }}" rel="stylesheet">

<div class="header">
    <h1>FACTURE</h1>
    <p>{{ $company_name }}</p>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Produit</th>
            <th>Quantité</th>
            <th>Prix Unitaire</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['quantity'] }}</td>
                <td>{{ $item['price'] }} €</td>
                <td>{{ $item['price'] * $item['quantity'] }} €</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    <p>Total : <strong>{{ $total }} €</strong></p>
</div>
