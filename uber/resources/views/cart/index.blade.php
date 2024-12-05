@extends('layouts.header')

<h1>Mon Panier</h1>

@if (count($cart) > 0)
<table>
    <thead>
        <tr>
            <th>Image</th>
            <th>Produit</th>
            <th>Prix</th>
            <th>Quantité</th>
            <th>Total</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($cart as $uniqueId => $item)
        <tr>
            <td><img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" style="width: 50px; height: 50px;"></td>
            <td>{{ $item['name'] }}</td>
            <td>{{ number_format((float) $item['price'], 2) }} €</td>
            <td>
                <form action="{{ route('cart.update', $uniqueId) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" />
                    <button type="submit">Mettre à jour</button>
                </form>
            </td>
            <td>{{ number_format ((float)($item['price']) * $item['quantity'], 2) }} €</td>
            <td>
                <form action="{{ route('cart.remove', $uniqueId) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Supprimer</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<p>Total : {{ number_format($total, 2) }} €</p>
@else
    <p>Votre panier est vide.</p>
@endif

    <script src="{{ asset('js/main.js') }}"></script>