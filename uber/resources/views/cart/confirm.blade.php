@extends('layouts.header')
<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />




<div id="panier-main">
    <div class="panier-header">
        <h1 class="panier-title">Mon Panier 🛒</h1>
    </div>
    @if (count($menus) > 0 || count($plats) > 0 || count($produits) > 0)
        <!-- Menus -->
        @if (count($menus) > 0)
        <h2>Menus</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Menu</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($menus as $key => $item)
                            <tr>
                                <td><img src="{{ $item['image'] }}" alt="{{ $item['name'] }}"
                                        style="width: 50px; height: 50px;"></td>
                                <td>{{ $item['name'] }}</td>
                                <td>{{ number_format((float) $item['price'], 2) }} €</td>
                                <td>
                                    <form action="{{ route('cart.update', $key) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}"
                                            min="1" />
                                        <button class="btn-submit" type="submit">Mettre à jour</button>
                                    </form>
                                </td>
                                <td>{{ number_format($item['price'] * $item['quantity'], 2) }} €</td>
                                <td>
                                    <form action="{{ route('cart.remove', $key) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-submit-sup" type="submit">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Plats -->
        @if (count($plats) > 0)
        <h2>Plats</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Plat</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($plats as $key => $item)
                            <tr>
                                <td><img src="{{ $item['image'] }}" alt="{{ $item['name'] }}"
                                        style="width: 50px; height: 50px;"></td>
                                <td>{{ $item['name'] }}</td>
                                <td>{{ number_format((float) $item['price'], 2) }} €</td>
                                <td>
                                    <form action="{{ route('cart.update', $key) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}"
                                            min="1" />
                                        <button class="btn-submit" type="submit">Mettre à jour</button>
                                    </form>
                                </td>
                                <td>{{ number_format($item['price'] * $item['quantity'], 2) }} €</td>
                                <td>
                                    <form action="{{ route('cart.remove', $key) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-submit-sup" type="submit">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Produits -->
        @if (count($produits) > 0)
        <h2>Produits</h2>
            <div class="table-container">
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
                        @foreach ($produits as $key => $item)
                            <tr>
                                <td><img src="{{ $item['image'] }}" alt="{{ $item['name'] }}"
                                        style="width: 50px; height: 50px;"></td>
                                <td>{{ $item['name'] }}</td>
                                <td>{{ number_format((float) $item['price'], 2) }} €</td>
                                <td>
                                    <form action="{{ route('cart.update', $key) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}"
                                            min="1" />
                                        <button class="btn-submit" type="submit">Mettre à jour</button>
                                    </form>
                                </td>
                                <td>{{ number_format($item['price'] * $item['quantity'], 2) }} €</td>
                                <td>
                                    <form action="{{ route('cart.remove', $key) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-submit-sup" type="submit">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <p class="panierTotal">Total : {{ number_format($total, 2) }} €</p>
    @else
        <p id="panier-vide">Votre panier est vide 😭</p>
    @endif
</div>

@if (session('message'))
    <div class="alert">
        {{ session('message') }}
    </div>
@endif

@if (count($menus) > 0 || count($plats) > 0 || count($produits) > 0)
<div class="panier-footer">
    <!-- Vérifier s'il y a des adresses enregistrées -->
    @if ($adresses->isEmpty())
        <p>Vous n'avez pas d'adresse enregistrée. Veuillez <a href="{{ route('ajtadresse', ['from' => 'cart']) }}">ajouter une adresse</a> pour valider votre panier.</p>
    @else
    <form action="{{ route('valider.panier') }}" method="POST">
        @csrf
        <!-- Sélection de l'adresse -->
        <p>Choisissez une adresse pour la livraison :</p>
        <select name="adresse" required>
            @foreach ($adresses as $adresse)
                <option value="{{ $adresse->id_adresse }}">
                    {{ $adresse->rue }}, {{ $adresse->cp }}, {{ $adresse->ville }}
                </option>
            @endforeach
        </select>

        <!-- Sélection de la carte bancaire -->
        <p>Choisissez une carte bancaire pour le paiement :</p>
        <select name="carte" required>
            @foreach ($cartes as $carte)
                <option value="{{ $carte->id_cb }}">
                    {{ substr($carte->num_cb, 0, 4) }} **** **** {{ substr($carte->num_cb, -4) }}
                </option>
            @endforeach
        </select>

        <!-- Bouton de validation -->
        <button type="submit" class="btn btn-success">Valider ma commande</button>
    </form>

    @endif
</div>

@else
    <p id="panier-vide">Votre panier est vide 😭</p>
@endif