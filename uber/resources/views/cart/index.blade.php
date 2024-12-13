@extends('layouts.header')
<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />

<div id="panier-main">
    <div class="panier-header">
        <h1 class="panier-title">Mon Panier 🛒</h1>
    </div>
    @if (count($menus) > 0 || count($plats) > 0 || count($produits) > 0)
        <!-- Menus -->
        <h2>Menus</h2>
        @if (count($menus) > 0)
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
        @else
            <p class="panierTotal">Il n'y a pas de menus dans votre panier.</p>
        @endif

        <!-- Plats -->
        <h2>Plats</h2>
        @if (count($plats) > 0)
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
        @else
            <p class="panierTotal">Il n'y a pas de plats dans votre panier.</p>
        @endif

        <!-- Produits -->
        <h2>Produits</h2>
        @if (count($produits) > 0)
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
        @else
            <p class="panierTotal">Il n'y a pas de produits dans votre panier.</p>
        @endif

        <p class="panierTotal">Total : {{ number_format($total, 2) }} €</p>
        <div class="commande">
            <a href="{{route('cart.confirm')}}">Passer commande</a>
        </div>

    @else
        <p id="panier-vide">Votre panier est vide 😭</p>
    @endif
</div>


<script src="{{ asset('js/main.js') }}"></script>
