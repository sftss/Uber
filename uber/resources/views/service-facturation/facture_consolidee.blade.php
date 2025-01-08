<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Facture consolidée') }}</title>
    <style>
        /* Global */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            padding: 2rem;
        }

        .container-img-logo {
            margin: 0 auto;
            transform: translate(-33%, -20%);
        }

        #heading {
            display: flex;
        }

        #ADroite {
            margin: 0 75% 0 0;
            transform: translate(0, 100%);
        }

        #AGauche {
            margin: 0 auto 0 59%;
        }

        /* En-tête */
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            margin-bottom: 1rem;
        }

        .header h1 {
            margin: 0;
            color: #000;
        }

        .header p {
            margin: 0.5rem 0;
            font-size: 1rem;
        }

        .client-info,
        .course-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .section {
            width: 100%;
        }

        .section h3 {
            margin-bottom: 0.5rem;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 0.3rem;
        }

        .section p {
            margin: 0.3rem 0;
        }

        /* Tableau des produits */
        table {
            width: 100%;
            border-spacing: 0;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }

        table.products th {
            background-color: #000;
            color: #fff;
            padding: 0.75rem;
            text-align: left;
        }

        table.products tr.items:nth-child(odd) {
            background-color: #f1f5f9;
        }

        table.products tr.items:nth-child(even) {
            background-color: #fff;
        }

        table.products td {
            padding: 0.75rem;
            border: 1px solid #ddd;
        }

        /* Total et Footer */
        .footer {
            text-align: center;
            margin-top: 1rem;
            padding-top: 0.5rem;
            border-top: 2px solid #ddd;
            font-size: 0.9rem;
            color: #555;
        }

        .footer p {
            margin: 0.5rem 0;
        }

        .footer strong {
            color: #333;
        }

        #totalTxt {
            display: flex;
            gap: 5px;
            flex-direction: row-reverse;
            font-size: 1.5rem
        }
    </style>
</head>
<body>
    
    <h1>{{ __('Facture pour les courses de la période') }}</h1>
    
    <p>{{ __('Chauffeur') }} : {{ $chauffeur->nom_chauffeur }} {{ $chauffeur->prenom_chauffeur }}</p>

    <!-- Tableau des items (détails des courses et pourboires) -->
    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>{{ __('Description') }}</th>
                <th>{{ __('Montant HT') }}</th>
                <th>{{ __('TVA') }}</th>
                <th>{{ __('Pourboire') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ number_format($item['price'], 2, ',', ' ') }} €</td>
                    <td>{{ $item['tva'] }}</td>
                    <td>
                        <!-- Affichage du pourboire dans la même ligne -->
                        @if ($item['tip'] !== null)
                            {{ number_format($item['tip'], 2, ',', ' ') }} €
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totaux -->
    <p><strong>{{ __('Total HT') }} : {{ number_format($totalHT, 2, ',', ' ') }} €</strong></p>
    <p><strong>{{ __('TVA (20%)') }} : {{ number_format($tva, 2, ',', ' ') }} €</strong></p>
    <p><strong>{{ __('Total TTC') }} : {{ number_format($totalTTC, 2, ',', ' ') }} €</strong></p>
    <p><strong>{{ __('Pourboire total') }} : {{ number_format($pourboire, 2, ',', ' ') }} €</strong></p>
</body>
</html>
