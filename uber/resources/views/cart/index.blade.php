@extends('layouts.header')

<h1>Mon Panier</h1>

@if (count($cart) > 0)
    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Prix</th>
                <th>Quantité</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cart as $id => $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ number_format($item['price'], 2) }} €</td>
                    <td>
                        <form action="{{ route('cart.update', $id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" />
                            <button type="submit">Mettre à jour</button>
                        </form>
                    </td>
                    <td>{{ number_format($item['price'] * $item['quantity'], 2) }} €</td>
                    <td>
                        <form action="{{ route('cart.remove', $id) }}" method="POST">
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